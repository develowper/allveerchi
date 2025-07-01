import {usePage} from "@inertiajs/vue3";
import mitt from "mitt";

export const emitter = mitt()

export function dir() {
    let $lang = usePage().props.language
    if ($lang == 'en') return 'ltr'
    else return 'rtl'
}

export function getQueryParams(url) {
    const params = new URL(url).searchParams;
    return Object.fromEntries(params.entries());
}

export function __(key, replace = {}) {
    let $lang = usePage().props.language;

    var translation = $lang[key]
        ? $lang[key]
        : key

    Object.keys(replace).forEach(function (key) {
        translation = translation.replace(`{${key}}`, replace[key])
    });

    return translation
}

export function log(str) {
    console.log(str);
}

export function getErrors(error) {
    if (error.response) {
        if (error.response.status == 419)
            location.reload();
        if (error.response.data && error.response.data.errors)
            return Object.values(error.response.data.errors).join("<br/>")
        if (error.response.data && error.response.data.message)
            if (error.response.data.message == 'Unauthenticated.')
                return this.__('first_login_or_register');
        return error.response.data.message;

    } else if (error.request) {
        return error.request;
    } else {
        return error.message;
    }
}

export function showToast(type, message) {
    emitter.emit('showToast', {type, message});
}
