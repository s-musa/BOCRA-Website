import Dinero from 'dinero.js/build/esm/dinero.min'

import Vue from 'vue'

Dinero.globalLocale = 'en-Ke'
Dinero.defaultCurrency = 'KES'
Object.defineProperty(Vue.prototype, '$money', {value: Dinero})

export {Dinero}

export function toPrice(amount, factor = Math.pow(10, 2)) {
   return Dinero({amount: Math.round(amount * factor), currency: 'KES'})
}
