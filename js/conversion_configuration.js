const addReplacementOptionBtn = document.querySelector('#add-replacement-option-btn');
const replacementOptionsContainer = document.querySelector('#replacement-options-container');
const replacementOptionTypeSelector = document.querySelector('#replacement-type');
const replacementOptionClass = 'replacement-option';

const submitBtn = document.querySelector('#submit-btn');

submitBtn.addEventListener('click', onSubmit);
addReplacementOptionBtn.addEventListener('click', onReplacementOptionAdd);

function onSubmit(event) {
    var replacementOptionsCounter = 1;
    const replacementOptions = replacementOptionsContainer.children;

    for (var i = 0; i < replacementOptions.length; i++) {
        const inputs = replacementOptions[i].children;

        for (var j = 0; j < inputs.length; j++) {
            inputs[j].setAttribute('name', inputs[j].getAttribute('name') + '-' + replacementOptionsCounter);
        }

        replacementOptionsCounter++;
    }
}

function onReplacementOptionAdd(event) {
    event.preventDefault();

    const replacementType = replacementOptionTypeSelector.value;

    if (replacementType === 'replace-tag') {
        createTagReplacementOption();
    } else if (replacementType === 'replace-value') {
        createValueReplacementOption();
    }
}

function createTagReplacementOption() {
    const option = createTwoFieldReplacementOption();

    option.querySelector('input[name=repl-option-first]').setAttribute('placeholder', 'Предишен таг');
    option.querySelector('input[name=repl-option-second]').setAttribute('placeholder', 'Нов таг');
    option.querySelector('input[type=hidden]').setAttribute('value', 'replace-tag');
}

function createValueReplacementOption() {
    const option = createTwoFieldReplacementOption();

    option.querySelector('input[name=repl-option-first]').setAttribute('placeholder', 'Таг');
    option.querySelector('input[name=repl-option-second]').setAttribute('placeholder', 'Нова стойност');
    option.querySelector('input[type=hidden]').setAttribute('value', 'replace-value');
}

function createTwoFieldReplacementOption() {
    // Create parent element

    const optionNode = document.createElement('section');
    optionNode.classList.add(replacementOptionClass);

    // Create inputs

    const previousValueInput = document.createElement('input');
    previousValueInput.setAttribute('type', 'text');
    previousValueInput.setAttribute('name', 'repl-option-first');
    previousValueInput.setAttribute('required', '');
    
    const newValueInput = document.createElement('input');
    newValueInput.setAttribute('type', 'text');
    newValueInput.setAttribute('name', 'repl-option-second');
    newValueInput.setAttribute('required', '');

    // Create hidden input to denote replacement type

    const replacementTypeInput = document.createElement('input');
    replacementTypeInput.setAttribute('type', 'hidden');
    replacementTypeInput.setAttribute('name', 'repl-option-type');

    // Create remove button

    const removeButton = document.createElement('button');
    const removeButtonText = document.createTextNode('-');

    removeButton.appendChild(removeButtonText);

    removeButton.addEventListener('click', removeReplacementOption);

    // Structure hierarchy

    optionNode.appendChild(previousValueInput);
    optionNode.appendChild(newValueInput);
    optionNode.appendChild(replacementTypeInput);
    optionNode.appendChild(removeButton);

    replacementOptionsContainer.appendChild(optionNode);

    return optionNode;
}

function removeReplacementOption(event) {
    event.preventDefault();

    event.target.parentElement.remove();
}