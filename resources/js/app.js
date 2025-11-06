import "./bootstrap";

// Currency
const intl_currency = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0,
    minimumFractionDigits: 0
});
const intl_percentage = new Intl.NumberFormat("id-ID", {
    style: "percent",
    maximumFractionDigits: 2,
    maximumFractionDigits: 2
});

window.format_percentage = (nominal) => {
    const formatted_percentage = intl_percentage.format(nominal);
    return formatted_percentage;
};

window.format_currency = (nominal) => {
    const formatted_currency = intl_currency.format(nominal);
    return formatted_currency;
};

window.preview_currency_element = (e, el) => {
    const element = document.getElementById(el);
    element.innerHTML = window.format_currency(e.value);
};
window.preview_percentage_element = (e, el) => {
    let value = parseFloat(e.value ?? 0);
    const element = document.getElementById(el);
    if (isNaN(value)) {
        element.innerHTML = "0%";
        return;
    }
    element.innerHTML = window.format_percentage(value / 100) ?? 0;
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

/**
 * Etc
 */

window.submit_form = (id) => {
    const form = document.getElementById(id);
    form.submit();
};

window.toggle_hidden_element = (e, el, target_value) => {
    const value_true = e.checked ?? e.value;
    const target_element = document.getElementById(el);
    if (value_true === target_value) {
        target_element.hidden = true;
    } else {
        target_element.hidden = false;
    }
};

