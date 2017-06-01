import Blockly from 'node-blockly/browser';
export default {
    json:{
        "type": "implement",
        "message0": "%1 %2 %3",
        "args0": [
            {
                "type": "field_dropdown",
                "name": "do",
                "options": [
                    [
                        "预算减",
                        "-"
                    ],
                    [
                        "预算加",
                        "+"
                    ],
                    [
                        "预算乘",
                        "*"
                    ],
                    [
                        "预算除",
                        "/"
                    ],
                    [
                        "预算调整为",
                        "="
                    ],
                    [
                        "停止投放",
                        "x"
                    ]
                ]
            },
            {
                "type": "field_input",
                "name": "value",
                "text": "0%"
            },
            {
                "type": "input_value",
                "name": "input"
            }
        ],
        "previousStatement": null,
        "colour": 230,
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
            var dropdown_do = block.getFieldValue('do');
            var text_value = block.getFieldValue('value');
            var value_input = Blockly.PHP.valueToCode(block, 'input', Blockly.PHP.ORDER_ATOMIC);
            // TODO: Assemble PHP into code variable.
            var code = dropdown_do+'#'+text_value+';\n';
            return code;
        };
    }

}