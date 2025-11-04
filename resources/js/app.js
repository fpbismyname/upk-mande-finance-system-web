import "./bootstrap";

// Currency
const intl = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0,
    minimumFractionDigits: 0
});

window.format_currency = (nominal) => {
    const formatted_currency = intl.format(nominal);
    return formatted_currency;
};

window.currency_format_element = (e, el) => {
    const element = document.getElementById(el);
    element.innerHTML = window.format_currency(e.value);
};

/**
 * Modal event
 */
window.open_modal = (id) => {
    const modal = document.getElementById(id);
    modal.showModal();
};
window.close_modal = (id) => {
    const modal = document.getElementById(id);
    modal.close();
};
