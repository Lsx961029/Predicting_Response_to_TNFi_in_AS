import requests
import argparse


class AS_Mdoel_RW(object):
    def __init__(self):
        # Available models
        self.model_targets = ["Major","NR"]
        # RF can only be supported on a local host.
        # For some reasons, the pickle file cannot be loaded if the flask was
        # launched by apache2 (ubuntu) on the AWS EC2 server
        #self.model_names = ["RF","LR"]
        self.model_names = ["LR"]
        self.model_tags = ["FullModel","ReducedModel"]

        # Input variables
        v_full = ['AGE', 'BASDAI_1', 'BASDAI_2', 'BASDAI_3', 'BASDAI_4', 'BASDAI_5', 'BASDAI_6',
                  'BASFI', 'BMI', 'MTX', 'NTP', 'PGA', 'SEX_M', 'SSZ', 'STEROID', 'TBP', 'UVEITIS',
                  'CRP_L', 'DisDur_L', 'HLA_B27_P', 'HLA_B27_N']
        v_lr_major = ['CRP_L', 'BMI', 'PGA', 'BASDAI_2', 'BASFI']
        v_lr_nr = ['CRP_L', 'AGE', 'BASDAI_2', 'PGA', 'BASFI']
        v_rf_major = ['CRP_L', 'BMI', 'PGA']
        v_rf_nr = ['CRP_L', 'AGE', 'BASDAI_2']

        self.variables = {}
        for name in self.model_names:
            self.variables[name] = {}
            for target in self.model_targets:
                self.variables[name][target] = {}
                for tag in self.model_tags:
                    if tag == "FullModel":
                        self.variables[name][target][tag] = v_full
                    else:
                        t = "v_{}_{}".format(name.lower(),target.split("_")[0].lower())
                        self.variables[name][target][tag] = eval(t)

        print("Independent variables have been successfully initialized")

    @staticmethod
    def case_insensitive_match(v: str, l: list):
        if v:
            for t in l:
                if v.lower() == t.lower():
                    return t
        return None

    @staticmethod
    def case_insensitive_fetch(v: str, d: dict):
        if v:
            for t in d:
                if v.lower() == t.lower():
                    return d[t]
        return None

    @staticmethod
    def args_get(args: dict, v: str, msg: str):
        res = AS_Mdoel_RW.case_insensitive_fetch(v, args)
        if res is None:
            res = input(msg)
            args[v] = res
        return res

    def format_inputs(self, args: dict):
        res = {}
        # this is the function to check the validity of input dict and reformat it
        #name = AS_Mdoel_RW.args_get(args, "model", "Choose your model [LR or RF]:")
        name = AS_Mdoel_RW.args_get(args, "model", "Choose your model [LR]:")
        name = AS_Mdoel_RW.case_insensitive_match(name, self.model_names)
        if name is None:
            return None, "model is not defined or its value is not valid: {}".format(name)
        res['model'] = name
        target = AS_Mdoel_RW.args_get(args, "target", "Choose your outcome target [Major or NR]:")
        target = AS_Mdoel_RW.case_insensitive_match(target, self.model_targets)
        if target is None:
            return None, "target is not defined or its value is not valid: {}".format(target)
        res['target'] = target
        tag = AS_Mdoel_RW.args_get(args, "tag", "Choose your inputs [FullModel or ReducedModel]:")
        tag = AS_Mdoel_RW.case_insensitive_match(tag, self.model_tags)
        if tag is None:
            return None, "tag is not defined or its value is not valid: {}".format(tag)
        res['tag'] = tag
        variables = self.variables[name][target][tag]
        # make sure all the required variables are there
        for k in variables:
            v = AS_Mdoel_RW.case_insensitive_fetch(k,args)
            if v is None:
                v = input("Enter the value of {}: ".format(k))
            res[k] = float(v)
        return True, res

    def query_api(self, args: dict):
        host = "http://52.15.161.152/api/run"
        print ("querying params = {}".format(args))
        response = requests.get(host,params=args)
        return response.content, response.json()

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Run query for RW AS predictive models')

    str_fields = ['model','target','tag']
    parsed, unknown = parser.parse_known_args()
    for arg in unknown:
        if arg.startswith(("-", "--")):
            # you can pass remaining variables through command line as well
            if(arg.replace('-','') in str_fields):
                parser.add_argument(arg, type=str)
            else:
                parser.add_argument(arg, type=float)
    parsed = parser.parse_args()
    args = vars(parsed)
    print (args)

    model = AS_Mdoel_RW()
    status, params = model.format_inputs(args)
    if status:
        content, rjson = model.query_api(params)
        if isinstance(rjson,dict) and "prediction" in rjson:
            for k,v in rjson.items():
                if k=="target":
                    target=v
                print("{} = {}".format(k,v))
            p = bool(rjson["prediction"])
            pa = rjson["probability"]
            if isinstance(pa, list):
                pa = pa[-1]
                if target == "Major":
                    if pa < 0.01:
                        print("Major response: The probability of achieving major response at 12 weeks is < 1%.")
                    elif pa > 0.99:
                        print("Major response: The probability of achieving major response at 12 weeks is > 99%.")
                    else:
                        print("Major response: The probability of achieving major response at 12 weeks is {}%.".format(round(pa*100)))
                else:
                    if pa < 0.01:
                        print("No response: The probability of having no  response at 12 weeks is < 1%.")
                    elif pa > 0.99:
                        print("No response: The probability of having no response at 12 weeks is > 99%.")
                    else:
                        print("No response: The probability of having no response at 12 weeks is {}%.".format(round(pa*100)))
        else:
            print(content)
    else:
        print(params)


