import Blockly from 'node-blockly/browser';
export default {
    json:{
        "type": "data_comparison",
        "message0": "%1 %2 %3 %4 %5",
        "args0": [
            {
                "type": "field_dropdown",
                "name": "date",
                "options": [
                    [
                        "今日",
                        "0"
                    ],
                    [
                        "最近7天",
                        "7"
                    ],
                    [
                        "最近14天",
                        "14"
                    ]
                ]
            },
            {
                "type": "field_dropdown",
                "name": "field",
                "options": [
                    [
                        "花费（Amount Spent）",
                        "getAmountSpent"
                    ],
                    [
                        "预算（Budget）",
                        "getBudget"
                    ],
                    [
                        "购买数量（Purchase）",
                        "getPurchase"
                    ],
                    [
                        "收入（Purchase Value）",
                        "getPurchaseValue"
                    ],
                    [
                        "花费/收入（ROAS）",
                        "getROAS"
                    ],
                    [
                        "收入/花费（ROI）",
                        "getROI"
                    ],
                    [
                        "加购物车数量（Add Cart）",
                        "getAddCart"
                    ],
                    [
                        "加购物车成本（CPA）",
                        "getCPA"
                    ],
                    [
                        "单次点击费用（CPC）",
                        "getCPC"
                    ],
                    [
                        "广告系列名（Campaign Name）",
                        "getCampaignName"
                    ],
                    [
                        "广告组名（Adset Name）",
                        "getAdsetName"
                    ],
                    [
                        "广告名（Ad Name）",
                        "getAdName"
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
                "name": "NAME"
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
            var dropdown_date = block.getFieldValue('date');
            var dropdown_field = block.getFieldValue('field');
            var dropdown_expression = block.getFieldValue('expression');
            var text_value = block.getFieldValue('input_value');
            var value_input = Blockly.PHP.valueToCode(block, 'input', Blockly.PHP.ORDER_ATOMIC);
            // TODO: Assemble PHP into code variable.
            //var code = '$'+dropdown_field+' '+dropdown_expression+' '+text_value;
            var code = '$this->expression("'+dropdown_date+'","'+dropdown_field+'","'+dropdown_expression+'","'+text_value.replace(/[ $]/g,'')+'")';
            // TODO: Change ORDER_NONE to the correct strength.
            return [code, Blockly.PHP.ORDER_NONE];
        };
    }

}