function getElementByXpath(path) {
    return document.evaluate(path, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
}

function waitForElement(xpath, callback) {
    const intervalId = setInterval(() => {
        element =getElementByXpath(xpath);
        if (element) {
            clearInterval(intervalId);
            callback(element);
        }
    }, 500);
}

document.addEventListener('DOMContentLoaded', () => {
    waitForElement('//div[contains(@style,"width: 260px")]', (element) => {        
        element.style = "width: 600px;"
    });

    waitForElement('//div[contains(@style,"max-height: 350px;")]', (element) => {        
        element.style = "max-height: 600px;"
    });
});

document.addEventListener('inertia:finish', () => {
    waitForElement('//div[contains(@style,"width: 260px")]', (element) => {        
        element.style = "width: 600px;"
    });

    waitForElement('//div[contains(@style,"max-height: 350px;")]', (element) => {        
        element.style = "max-height: 600px;"
    });
});