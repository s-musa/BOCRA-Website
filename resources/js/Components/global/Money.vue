<template>
   <money3 v-model="amount" v-bind="config" :prefix="config.prefix" class="form-control"></money3>
</template>

<script>
import { Money3Component } from 'v-money3'

export default {
   components: { money3: Money3Component },
   props: {
      prefix: {
         type: String,
         default: 'KES'
      },
   },
   data () {
      return {
         amount: '',
         config: {
            masked: false,
            prefix: this.prefix + ' ',
            suffix: '',
            thousands: ',',
            decimal: '.',
            precision: 2,
            disableNegative: false,
            disabled: false,
            min: null,
            max: null,
            allowBlank: false,
            minimumNumberOfCharacters: 0,
            shouldRound: true,
            focusOnRight: false,
         }
      }
   }
}
</script>
