import Blockly from 'node-blockly/browser';
export default {
    json:{
        "type": "data_comparison",
        "message0": "%1 %2 %3 %4",
        "args0": [
            {
                "type": "field_dropdown",
                "name": "field",
                "options": [
                    [
                        "今日花费",
                        "getNowAmountSpent"
                    ],
                    [
                        "当前预算",
                        "getBudget"
                    ],
                    [
                        "purchase",
                        "getPurchase"
                    ],
                    [
                        "ROAS",
                        "getROAS"
                    ],
                    [
                        "加购物车数量",
                        "getAddCart"
                    ],
                    [
                        "CPC",
                        "getCPC"
                    ],
                    [
                        "广告组名",
                        "getAdsetName"
                    ],
                    [
                        "广告系列名",
                        "getCampaignName"
                    ]
                ]
            },
            {
                "type": "field_dropdown",
                "name": "expression",
                "options": [
                    [
                        "大于",
                        ">"
                    ],
                    [
                        "大于等于",
                        ">="
                    ],
                    [
                        "小于",
                        "<"
                    ],
                    [
                        "小于等于",
                        "<="
                    ],
                    [
                        "等于",
                        "=="
                    ],
                    [
                        "不包含",
                        "NLI"
                    ],
                    [
                        "包含",
                        "LI"
                    ]
                ]
            },
            {
                "type": "field_input",
                "name": "input_value",
                "text": "value"
            },
            {
                "type": "input_value",
                "name": "default"
            }
        ],
        "output": null,
        "colour": 230,
        "tooltip": "",
        "helpUrl": ""
    },
    init: function(){
        var that=this;
        Blockly.Blocks['data_comparison'] = {

            init: function() {
                this.jsonInit(that.json);
            }
        }
        Blockly.PHP['data_comparison'] = function(block) {
            var dropdown_field = block.getFieldValue('field');
            var dropdown_expression = block.getFieldValue('expression');
            var text_value = block.getFieldValue('input_value');
            var value_input = Blockly.PHP.valueToCode(block, 'input', Blockly.PHP.ORDER_ATOMIC);
            // TODO: Assemble PHP into code variable.
            //var code = '$'+dropdown_field+' '+dropdown_expression+' '+text_value;
            var code = '$this->expression("'+dropdown_field+'","'+dropdown_expression+'","'+text_value.replace(/[ $]/g,'')+'")';
            // TODO: Change ORDER_NONE to the correct strength.
            return [code, Blockly.PHP.ORDER_NONE];
        };
    }

}