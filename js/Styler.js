//táto trieda si uchováva všetky hodnoty CSS vlastností všetkých elementov príkladu

class Styler {

    constructor() {
        this.propList = {};
    }

    changeExample(newPropArray) {
        this.propList = newPropArray;
    }

    updateValue(elemName, propName, propValue) {
        this.propList[elemName][propName] = propValue;      //todo aby sme nemohli zmenit value a aj tak nam to insertne do propList
    }

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
}