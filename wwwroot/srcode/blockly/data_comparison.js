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
                        "Campaign Name",
                        "Campaign Name"
                    ],
                    [
                        "Ad Sets Name",
                        "Ad Sets Name"
                    ],
                    [
                        "Ad Name",
                        "Ad Name"
                    ],
                    [
                        "Delivery",
                        "Delivery"
                    ],
                    [
                        "Results",
                        "Results"
                    ],
                    [
                        "Reach",
                        "Reach"
                    ],
                    [
                        "Cost per Result",
                        "Cost per Result"
                    ],
                    [
                        "Amount Spent",
                        "Amount Spent"
                    ],
                    [
                        "Link Clicks",
                        "Link Clicks"
                    ],
                    [
                        "Website Purchases",
                        "Website Purchases"
                    ],
                    [
                        "Clicks (All)",
                        "Clicks (All)"
                    ],
                    [
                        "CTR (All)",
                        "CTR (All)"
                    ],
                    [
                        "CPC (All)",
                        "CPC (All)"
                    ],
                    [
                        "Impressions",
                        "Impressions"
                    ],
                    [
                        "CPM (Cost per 1,000 Impressions)",
                        "CPM (Cost per 1,000 Impressions)"
                    ],
                    [
                        "CPC (Cost per Link Click)",
                        "CPC (Cost per Link Click)"
                    ],
                    [
                        "CTR (Link Click-Through Rate)",
                        "CTR (Link Click-Through Rate)"
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
                        "包含",
                        "like"
                    ],
                    [
                        "不包含",
                        "not like"
                    ]
                ]
            },
            {
                "type": "field_input",
                "name": "value",
                "text": "value"
            },
            {
                "type": "input_value",
                "name": "input"
            }
        ],
        "inputsInline": false,
        "output": null,
        "colour": 330,
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
            var text_value = block.getFieldValue('value');
            var value_input = Blockly.PHP.valueToCode(block, 'input', Blockly.PHP.ORDER_ATOMIC);
            // TODO: Assemble PHP into code variable.
            var code = '$'+dropdown_field+' '+dropdown_expression+' '+text_value;
            // TODO: Change ORDER_NONE to the correct strength.
            return [code, Blockly.PHP.ORDER_NONE];
        };
    }

}