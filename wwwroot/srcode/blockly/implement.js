import Blockly from 'node-blockly/browser';
export default {
    json:{
        "type": "implement",
        "message0": "%1 %2 %3 %4 %5",
        "args0": [
            {
                "type": "field_dropdown",
                "name": "field",
                "options": [
                    [
                        "预算调整",
                        "Budget"
                    ],
                    [
                        "暂停投放",
                        "Pause"
                    ]
                ]
            },
            {
                "type": "field_dropdown",
                "name": "do",
                "options": [
                    [
                        "加上",
                        "+"
                    ],
                    [
                        "减去",
                        "-"
                    ],
                    [
                        "调至",
                        "="
                    ]
                ]
            },
            {
                "type": "field_dropdown",
                "name": "type",
                "options": [
                    [
                        "数值或百分比",
                        "input"
                    ],
                    [
                        "ROAS",
                        "ROAS"
                    ],
                    [
                        "今日花费",
                        "getAmountSpentNow"
                    ]
                ]
            },
            {
                "type": "field_input",
                "name": "value",
                "text": "0%    "
            },
            {
                "type": "input_value",
                "name": "implement"
            }
        ],
        "previousStatement": null,
        "colour": 135,
        "tooltip": "",
        "helpUrl": ""
    },
    init: function(){
        var that=this;
        Blockly.Blocks['implement'] = {

            init: function() {
                this.jsonInit(that.json);
            }
        }
        Blockly.PHP['implement'] = function(block) {
            var dropdown_field = block.getFieldValue('field');
            var dropdown_do = block.getFieldValue('do');
            var dropdown_type = block.getFieldValue('type');
            var text_value = block.getFieldValue('value');
            var value_implement = Blockly.JavaScript.valueToCode(block, 'implement', Blockly.JavaScript.ORDER_ATOMIC);
            // TODO: Assemble PHP into code variable.
            //var code = dropdown_do+'#'+text_value+';\n';
            var code = 'return $this->implement("'+dropdown_field+'","'+dropdown_do+'","'+dropdown_type+'","'+text_value.replace(/[ $]/g,'')+'");';
            return code;
        };
    }

}