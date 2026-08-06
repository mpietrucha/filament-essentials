export default Alpine => {
    Alpine.directive('decimal', (el, { expression }, { evaluate }) => {
        const fractionDigits = expression ? evaluate(expression) : 2

        const format = () => {
            const value = convertToDecimal(el.value)

            if (isNaN(value)) {
                return
            }

            el.value = value.toFixed(fractionDigits)
        }

        format()
        el.addEventListener('change', format)
    })
}

const DOT = '.'
const COMMA = ','

const convertToDecimal = value => {
    const decimal = value.lastIndexOf(COMMA) > value.lastIndexOf(DOT) ? COMMA : DOT
    const thousands = decimal === COMMA ? DOT : COMMA

    return parseFloat(value.split(thousands).join('').replace(decimal, DOT))
}
