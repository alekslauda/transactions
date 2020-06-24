<template>
    <div class="container">

        <div class="transfer-form">
            <makeTransferForm @transferMaded="handleTransferAdded"></makeTransferForm>
        </div>

        <div class="card card-default m-5" v-if="Object.entries(transaction).length > 0">
            <div class="card-header transaction-details-header text-center">
                <span>From: {{ transaction.sender.username }}</span>
                <i class='far fa-address-book' style='font-size:20px'></i>
                <span>To: {{ transaction.receiver.username }}</span>
            </div>

            <div class="card-body">

                <div v-if="has_error">
                    <p class="alert alert-danger">Something went wrong. Please try again later.</p>
                </div>

                <div v-if="success_update">
                    <p class="alert alert-success">Transaction status was updated successfully.</p>
                </div>

                <form>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" readonly :placeholder="getAmount(transaction.amount)">
                    </div>
                    <div class="form-group">
                        <label for="date">Date Created</label>
                        <input type="text" class="form-control" id="date" readonly :placeholder="transaction.created_at">
                    </div>
                    <div class="form-group" v-if="Object.entries(statuses).length > 0">
                        <label for="status">Status</label>
                        <select @change="changeStatus($event)" v-model="transaction.status_type" class="form-control" id="status">
                            <option :value="key" v-for="(item, key, index) in statuses">{{ item }}</option>
                        </select>
                    </div>
                    <div class="text-right">
                        <button @click="goBack()" class="btn btn-info text-uppercase">Go Back</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

    import makeTransferForm from '../../components/MakeTransferForm';

    export default {
        data() {
            return {
                transaction: {},
                statuses: {},
                has_error: false,
                success_update: false,
            }
        },

        components: {
            makeTransferForm
        },

        mounted() {
            this.getTransaction();
            this.getStatuses();
        },

        methods: {

            goBack() {
                this.$router.push('/recent-transactions')
            },

            getStatuses() {
                this.$http({
                    url: `transactions/statuses`,
                    method: 'GET'
                }).then(res => {
                    this.statuses = res.data.data;
                }).catch((err) => {
                    if (err.response && err.response.status === 404) {
                        this.$router.push('/404');
                    }
                    this.has_error = true;
                })
            },

            getAmount(amount) {
                return `$ ${amount}`;
            },

            getTransaction() {
                this.$http({
                    url: `transactions/${this.$route.params.id}`,
                    method: 'GET'
                }).then(res => {
                    this.transaction = res.data.data;
                }).catch((err) => {
                    if (err.response && err.response.status === 404) {
                        this.$router.push('/404');
                    }
                    this.has_error = true;
                })
            },

            handleTransferAdded($event) {
                this.$router.push('/recent-transactions')
            },

            changeStatus($event) {
                this.$loading(true);
                this.$http({
                    url: `transactions/${this.$route.params.id}`,
                    method: 'PATCH',
                    data: {
                        status: $event.target.value
                    }
                }).then(res => {
                    this.transaction = res.data.data;
                    this.success_update = true;
                    this.$loading(false);
                }).catch((err) => {
                    if (err.response && err.response.status === 404) {
                        this.$router.push('/404');
                    }
                    this.$loading(false);
                    this.has_error = true;
                    this.success_update = false;
                })
            }
        }
    }
</script>
<style>
    .transfer-form {
        float: left;
        margin-right: 50px;
        width: 100%;
        max-width: 20rem;
    }

    .transaction-details-header {
        color: white;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
</style>
