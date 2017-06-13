import Blockly from 'node-blockly/browser';
export default {
    json:{
        "type": "budget_limit",
        "message0": "%1 %2 %3",
        "args0": [
            {
                "type": "field_dropdown",
                "name": "type",
                "options": [
                    [
                        "预算上限",
                        "max"
                    ],
                    [
                        "预算下限",
                        "min"
                    ]
                ]
            },
            {
                "type": "field_input",
                "name": "value",
                "text": "0     "
            },
            {
                "type": "input_value",
                "name": "input"
            }
        ],
        "previousStatement": null,
        "nextStatement": null,

        
        "colour": 300,
        "tooltip": "请将此片段放在开始位置",
        "helpUrl": ""
    },
    init: function(){
        var that=this;
        Blockly.Blocks['budget_limit'] = {

            init: function() {
                this.jsonInit(that.json);
            }
        }
        Blockly.PHP['budget_limit'] = function(block) {
            var dropdown_type = block.getFieldValue('type');
            var text_value = block.getFieldValue('value');
            var value_input = Blockly.PHP.valueToCode(block, 'input', Blockly.PHP.ORDER_ATOMIC);
            // TODO: Assemble PHP into code variable.
            //var code = dropdown_do+'#'+text_value+';\n';
            var code = '$this->setBudgetLimit("'+dropdown_type+'","'+text_value.replace(/[ ]/g,'')+'");';
            return code;
        };
    }

}