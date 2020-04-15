<template>
    <div class="flex flex-col">
        <h1 class="border-b border-muted-dark text-xl font-medium p-1 mb-4 md:mb-3">Edit Bankroll Transaction</h1>
        <div class="flex flex-col items-center p-2">
            <div class="flex w-full items-center mb-3">
                <div class="w-1/4 font-medium">Date</div>
                <div class="w-3/4">
                    <datetime
                        v-model="date"
                        type="date"
                        input-class="w-full p-3"
                        :minute-step="5"
                        auto
                        title="Bankroll Transaction Date"
                        class="w-full bg-muted-light border border-muted-dark rounded border theme-green"
                        :class="errors.date ? 'border-red-700' : 'border-gray-400'"
                    ></datetime>
                </div>

            </div>
            <div class="flex w-full items-center mb-3">
                <div class="w-1/4 font-medium">Amount</div>
                <div class="w-3/4">
                    <input v-model="amount" type="number" step="0.01" :class="{ 'border-red-700' : errors.amount }"/>
                </div>
            </div>
        </div>
        <div class="flex justify-between p-2">
			<button @click.prevent="deleteTransaction" type="button" class="bg-red-500 hover:bg-red-600 focus:bg-red-600 rounded text-white text-sm px-4 py-2"><i class="fas fa-trash mr-3"></i><span>Delete</span></button>
			<button @click.prevent="saveTransaction" type="button" class="bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded text-white text-sm px-4 py-2"><i class="fas fa-check mr-3"></i><span>Save Changes</span></button>
		</div>
    </div>
</template>

<script>
export default {
	name: 'BankrollTransactionDetails',
    props: {
		bankrollTransaction: Object
    },
    data() {
        return {
            date: this.bankrollTransaction.date,
            amount: this.bankrollTransaction.amount,
            errors: {},
        }
    },
    methods: {
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
                            this.$modal.hide('dialog');
                            this.$emit('close');
                            this.$snotify.warning('Successfully deleted.');
						},
						class: 'bg-red-500 text-white'
					},

				],
			})
        },
        saveTransaction: function () {
            this.$emit('close');
            this.$snotify.success('Saved changes!');
        }
	}
}
</script>

<style>

</style>