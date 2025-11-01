import './bootstrap';

// Currency
const intl = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
    minimumFractionDigits: 0
})

// Submit Form
window.submit_form = (id) => {
    const form = document.getElementById(id);
    form.submit()
}

window.format_currency = (nominal)=>{
    const formatted_currency = intl.format(nominal)
    return formatted_currency
}

window.currency_format_element = (e, el) => {
    const element = document.getElementById(el);
    element.innerHTML = `(${window.format_currency(e.value)})`;
}