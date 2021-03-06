<template>
    <div class="flex flex-col">
        <h2 class="uppercase text-lg text-gray-200 font-extrabold tracking-wider mb-3">Edit Bankroll Transaction</h2>
        <div class="flex flex-col items-center">
            <div class="w-5/6 mx-auto mb-3">
                <datetime
                    v-model="editBankrollTransaction.date"
                    type="date"
                    value-zone="local"
                    :max-datetime="maxDateTime"
                    auto
                    title="Bankroll Transaction Date"
                    class="theme-green"
                    :input-class="{'error-input' : errors.date, 'w-full p-3' : true}"
                    @input="delete errors.date"
                ></datetime>
                <span v-if="errors.date" class="error-message">{{ errors.date[0] }}</span>
            </div>
            <div class="w-5/6 mx-auto mb-3">
                <transaction-amount
                    :allowNegative="true"
                    :currency="editBankrollTransaction.currency"
                    :amount="editBankrollTransaction.amount"
                    :error="errors.amount"
                    v-on:clear-error="delete errors.amount"
                    v-on:update-currency="editBankrollTransaction.currency = arguments[0]"
                    v-on:update-amount="editBankrollTransaction.amount = arguments[0]"
                />
                <span v-if="errors.amount" class="error-message">{{ errors.amount[0] }}</span>
            </div>
            <div class="w-5/6 mx-auto mb-3">
                <textarea
                    v-model="editBankrollTransaction.comments"
                    placeholder="Comments"
                    rows=4
                    :class="{ 'error-input' : errors.comments }"
                    @input="delete errors.comments"
                ></textarea>
                <span v-if="errors.comments" class="error-message">{{ errors.comments[0] }}</span>
            </div>
        </div>
        <div class="flex justify-between">
			<button @click.prevent="deleteTransaction" type="button" class="btn btn-red"><i class="fas fa-trash mr-3"></i><span>Delete</span></button>
			<button @click.prevent="saveTransaction" type="button" class="btn btn-green"><i class="fas fa-check mr-3"></i><span>Save Changes</span></button>
		</div>
    </div>
</template>

<script>
import TransactionAmount from '@components/TransactionAmount'

import { mapActions } from 'vuex'
import moment from 'moment'

export default {
    name: 'BankrollTransactionDetails',
    components: { TransactionAmount },
    props: {
		bankrollTransaction: Object
    },
    data() {
        return {
            editBankrollTransaction: {
                id: this.bankrollTransaction.id,
                date: moment.utc(this.bankrollTransaction.date).format(),
                currency: this.bankrollTransaction.currency ?? this.$store.state.user.currency,
                amount: this.bankrollTransaction.amount,
                comments: this.bankrollTransaction.comments,
            },
            errors: {},
            maxDateTime: moment().format(),
        }
    },
    methods: {
        ...mapActions('bankroll', ['updateBankrollTransaction', 'deleteBankrollTransaction']),
		deleteTransaction: function() {
			this.$modal.show('dialog', {
				title: 'Are you sure?',
				text: 'Are you sure you want to delete this bankroll transaction?  This action cannot be undone.',
				buttons: [
					{
						title: 'Cancel'
					},
					{
						title: 'Yes, delete.',
						handler: () => { 
                            this.deleteBankrollTransaction(this.bankrollTransaction)
                            .then(response => {
                                this.$modal.hide('dialog');
                                this.$emit('close');
                                this.$snotify.warning('Successfully deleted bankroll transaction.');
                            })
                            .catch(error => {
                                this.$snotify.error('Error: '+error.response.data.message);
                            })
						},
						class: 'bg-red-500 text-white'
					},

				],
			})
        },
        saveTransaction: function () {
            this.updateBankrollTransaction(this.editBankrollTransaction)
            .then(response => {
                this.$modal.hide('dialog');
                this.$emit('close');
                this.$snotify.success('Successfully updated bankroll transaction.');
            })
            .catch(error => {
                this.$snotify.error('Error: '+error.response.data.message);
                this.errors = error.response.data.errors
            })
        }
	}
}
</script>

<style>
    .v--modal-overlay .v--modal-box {
        overflow: visible;
    }
</style>