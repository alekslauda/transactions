<template>
    <div class="card card-default text-white">

        <div class="card-header recent-transactions-header">
            <i class='far fa-address-card' style='font-size:20px'></i>
            <span class="text-header">Make a Transfer</span>
        </div>

        <div class="card-body">
            <form @submit.prevent="transfer" method="post" action="" class="transfer-form-container">

                <p v-if="has_error" class="alert alert-danger">Invalid transfer. Please make sure you've entered the information needed for a transfer.</p>

                <div class="form-group">
                    <input type="text" id="from" :disabled="dropdownConfig.fromAccount.selected" required="required" placeholder="From: Amazon Online Shop"  v-model="transferData.fromAccount" @focus="focus($event, 'fromAccount')" @input="change($event, 'fromAccount')"/>
                    <label for="from" class="control-label">From Account</label><i class="bar"></i>
                    <button v-if="dropdownConfig.fromAccount.selected" style="margin-top: 5px" type="button" @click="deselect('fromAccount')">deselect</button>
                    <drop-down-list
                        @suggestionClicked="fromAccountSuggestion($event)"
                        :config="dropdownConfig.fromAccount"
                    >

                    </drop-down-list>
                </div>
                <div class="form-group">
                    <input type="text" id="to" :disabled="dropdownConfig.toAccount.selected" required="required" placeholder="Southern Electric Company" v-model="transferData.toAccount" @focus="focus($event, 'toAccount')" @input="change($event, 'toAccount')"/>
                    <label for="to" class="control-label">To Account</label><i class="bar"></i>
                    <button v-if="dropdownConfig.toAccount.selected" style="margin-top: 5px" type="button" @click="deselect('toAccount')">deselect</button>
                    <drop-down-list
                        @suggestionClicked="toAccountSuggestion($event)"
                        :config="dropdownConfig.toAccount"
                    >

                    </drop-down-list>
                </div>
                <div class="form-group">
                    <input type="number" min="1" step="any" id="amount" required="required" placeholder="$ 0.00" v-model="transferData.amount"/>
                    <label for="amount" class="control-label">Amount</label><i class="bar"></i>
                </div>
                <div class="form-group" v-if="Object.entries(paymentTypes).length > 0">
                    <select v-model="transferData.selectedPayment" id="payment-type">
                        <option :value="key"  v-for="(item, key, index) in paymentTypes">{{ item }}</option>
                    </select>
                    <label for="payment-type" class="control-label">Payment Type</label><i class="bar"></i>
                </div>
                <div class="button-container">
                    <button :disabled="loading" type="submit" class="button btn-transfer"><span>Submit</span></button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

    import DropDownList from './DropdownList'

    export default {

        components: {
            DropDownList
        },

        data() {
            return {
                loading: false,
                has_error: false,
                open: false,
                paymentTypes: {},
                transferData: {
                    toAccount: '',
                    fromAccount: '',
                    amount: '',
                    selectedPayment: 'cp',
                    meta: {
                        fromAccountId: '',
                        toAccountId: '',
                    }
                },
                dropdownConfig: {
                    fromAccount: {
                        open: false,
                        selection: '',
                        selected: false,
                        users: []
                    },
                    toAccount: {
                        open: false,
                        selection: '',
                        selected: false,
                        users: []
                    }
                },
            }
        },

        mounted() {
            this.getUsers();
            this.getPaymentTypes();
        },

        methods: {

            setUpUsers() {
                let users = [...this.users].map(user => user.username);
                this.dropdownConfig.fromAccount.users = users
                this.dropdownConfig.toAccount.users = users
            },

            getUsers() {
                this.$loading(true);
                this.$http({
                    url: `users`,
                    method: 'GET'
                })
                    .then((res) => {
                        this.$loading(false);
                        this.users = res.data.data.filter(user => user.username !== this.$auth.user().username)
                        this.setUpUsers();
                    }, () => {
                        this.$loading(false);
                        this.has_error = true
                    })
            },

            resetTransferData() {
                this.transferData = {
                        toAccount: '',
                        fromAccount: '',
                        amount: '',
                        selectedPayment: 'cp',
                        meta: {
                        fromAccountId: '',
                            toAccountId: '',
                    }
                }

                let types = ['fromAccount', 'toAccount'];
                types.forEach(type => this.deselect(type));
            },

            getPaymentTypes() {
                this.$loading(true);
                this.$http({
                    url: `transactions/paymentTypes`,
                    method: 'GET'
                })
                    .then((res) => {
                        this.$loading(false);
                        let that = this;
                        Vue.nextTick(function () {
                            that.paymentTypes = res.data.data;
                        })

                    }, () => {
                        this.$loading(false);
                    })
            },

            deselect(type) {
                this.dropdownConfig[type].selected = !this.dropdownConfig[type].selected
                this.dropdownConfig[type].selection = '';
                this.transferData[type] = ''
            },


            transfer() {

                this.has_error = (
                    this.transferData.fromAccount === this.transferData.toAccount ||
                    this.transferData.fromAccount === '' ||
                    this.transferData.toAccount === '' ||
                    !this.users.some(user => {
                        this.transferData.meta.toAccountId = user.id;
                        return user.username == this.transferData.toAccount
                    }) ||
                    !this.users.some(user => {
                        this.transferData.meta.fromAccountId = user.id;
                        return user.username == this.transferData.fromAccount
                    }) ||
                    this.transferData.amount === '' || this.transferData.amount === 0
                )

                if (!this.has_error) {
                    this.loading = true;
                    this.$loading(this.loading)
                    this.transferData.amount = +this.transferData.amount;
                    this.$http({
                        url: 'transactions',
                        method: 'POST',
                        data: this.transferData,
                    }).then((res) => {
                        this.$emit('transferMaded', res.data);
                        this.loading = false;
                        this.$loading(this.loading)
                        this.resetTransferData();
                    }, () => {
                        this.has_error = true
                        this.loading = false;
                        this.$loading(this.loading)
                    })
                }
            },

            fromAccountSuggestion(selection) {
                this.transferData.fromAccount = selection;
                this.dropdownConfig.fromAccount.open = false;
                this.dropdownConfig.fromAccount.selected = true;
            },

            toAccountSuggestion(selection) {
                this.transferData.toAccount = selection;
                this.dropdownConfig.toAccount.open = false;
                this.dropdownConfig.toAccount.selected = true;
            },

            focus($event, type) {

                if (type === 'fromAccount') {
                    this.dropdownConfig['toAccount'].open = false;
                } else {
                    this.dropdownConfig['fromAccount'].open = false;
                }

            },


            change($event, type) {
                this.dropdownConfig[type].selection = this.transferData[type];
                this.setUpUsers();

                if (this.dropdownConfig['fromAccount'].selected) {
                    this.dropdownConfig['toAccount'].users = this.dropdownConfig['toAccount'].users.filter(username => username != this.transferData.fromAccount)
                }

                if (this.dropdownConfig['toAccount'].selected) {
                    this.dropdownConfig['fromAccount'].users = this.dropdownConfig['fromAccount'].users.filter(username => username != this.transferData.toAccount)
                }

                if (this.dropdownConfig[type].open == false) {
                    this.dropdownConfig[type].open = true
                }
            },
        }
    }
</script>
<style scoped src="../assets/styles/styles.css">
</style>
