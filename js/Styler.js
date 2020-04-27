//táto trieda si uchováva všetky hodnoty CSS vlastností všetkých elementov príkladu

class Styler {

    constructor() {
        this.propList = {};
    }

    changeExample(newPropObj) {
        this.propList = newPropObj;
    }

    updateValue(elemName, propName, propValue) {
        this.propList[elemName][propName] = propValue;
    }

    clearAll() {
        for (let elem in this.propList) {
            for(let prop in this.propList[elem]) {
                this.propList[elem][prop] = "";
            }
        }
    }

    //výstup pôjde do <style> <iframe>
    getHTMLStyle() {
        let styleString = "";

        for (let elem in this.propList) {
            styleString += `${elem} { `;

            for(let prop in this.propList[elem]) {
                if (this.propList[elem][prop] !== "") {
                    styleString += `${prop}: ${this.propList[elem][prop]}; `;
                }
            }
            styleString += ` } `;
        }

        return styleString;
    }

    //výstup pôjde na zobrazenie užívateľovi
    getTextStyle() {
        let styleString = "";

        for (let elem in this.propList) {
            styleString += elem + ' {\n';

            for(let prop in this.propList[elem]) {
                if (this.propList[elem][prop] !== "") {
                    styleString += '<span class="prop">' + prop + '</span>: ' + this.propList[elem][prop] + ';\n';
                }
            }
            styleString += '}\n\n';
        }

        let regUnit = /(px|em|%)/g,
            regHexColor = /#([a-f0-9]{6}|[a-f0-9]{3})/gi,
            regNumber = /( [-+]?(\d*[.,])?\d+)/g;

        return styleString.replace(regUnit, '<span class="unit">$1</span>')
            .replace(regHexColor, '<span class="color">#$1</span>')
            .replace(regNumber, '<span class="number">$1</span>');
    }
}