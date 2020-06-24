<template>
    <div class="container">

        <div class="transfer-form">
            <makeTransferForm @transferMaded="handleTransfer($event)"></makeTransferForm>
        </div>

        <div class="card card-default m-5 text-white">
            <div class="card-header recent-transactions-header">
                <i class='fas fa-table' style='font-size:20px'></i>
                <span class="text-header">Recent Transactions</span>
            </div>

            <div class="card-body" v-if="transactions.length">
                <div class="form-group filters-container">
                    <div class="search-input col-md-5">
                        <input type="text" id="search" required="required" placeholder="Search by typing..." @input="search($event)" /><i class="bar"></i>
                    </div>
                    <div class="sort">
                        <span style="color: black; margin:8px 10px 0px 0px">Sort By</span>
                        <div class="sort-types">
                            <button type="button" v-for="typeObj in sortTypes" :class="typeObj.classes" @click="activate(typeObj)">{{ typeObj.label }}</button>
                        </div>
                    </div>
                </div>

                <table class="table" v-if="!noSearchResults">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Date Created</th>
                        <th scope="col">Info</th>
                        <th style="width: 20%" scope="col">Payment Type</th>
                        <th scope="col">Amount</th>
                        <th scope="col"></th>
                    </tr>
                    <tr :class="getClassBasedOnStatusType(transaction.status_type)" v-for="(transaction, index) in transactions"
                        style="margin-bottom: 5px;font-weight: bold">
                        <th scope="row">{{ index+1 }}</th>
                        <td>{{ transaction.created_at }}</td>
                        <td style="width: 30%">
                            <p>From: {{ transaction.sender.username }}</p>
                            <p>To: {{ transaction.receiver.username }}</p>
                        </td>
                        <td>{{ transaction.payment_type }}</td>
                        <td>{{ getAmount(transaction.amount) }}</td>
                        <td>
<!--                            <router-link :to="{ name: 'transaction.details', params: { id: transactions.id }}">Details</router-link>-->
                            <a href="javascript:void(0)" @click="goToDetails(transaction.id)">Details</a>
                        </td>
                    </tr>
                </table>
                <div style="text-align:center; color: black; margin-top: 10px" v-else>
                    <p>No transactions found.</p>
                </div>
            </div>
            <div style="text-align:center; color: black; margin-top: 10px" v-else>
                <div v-if="has_error">
                    <p class="alert">Something went wrong. Please try again later.</p>
                </div>
                <div v-else>
                    <p>No transactions found. </p>
                    <p>Please use the "Make a transfer" form on the left to perform a transaction.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import makeTransferForm from '../../components/MakeTransferForm'

    export default {

        data() {
            return {
                has_error: false,
                noSearchResults: false,
                users: [],
                transactions: [],
                copyTransactions: [],
                prevSortType: '',
                sortTypes: {
                    date: {
                        label: "Date",
                        type: 'date',
                        classes: ['btn', 'btn-light', 'text-uppercase']
                    },
                    beneficiant: {
                        type: 'beneficiant',
                        label: "Beneficiant",
                        classes: ['btn', 'btn-light', 'text-uppercase']
                    },
                    amount: {
                        type: 'amount',
                        label: "Amount",
                        classes: ['btn', 'btn-light', 'text-uppercase']
                    }
                }
            }
        },

        mounted() {
            this.getTransactions();
        },
        components: {
            makeTransferForm
        },

        methods: {

            goToDetails(id) {
                this.$router.push({name:'transaction.details',params:{id:id}})
            },

            handleTransfer($event) {
                this.getTransactions();
            },

            getClassBasedOnStatusType(statusType) {
                let className = `transaction-status-${statusType}`;
                let classes = {}
                classes[`${className}`] = true;
                return classes
            },

            activate(typeObj) {

                for (let t in this.sortTypes) {
                    let hasActiveClass = this.sortTypes[t].classes.indexOf('active-sort');
                    if (hasActiveClass !== -1) {
                        this.sortTypes[t].classes.splice(hasActiveClass, 1);
                    }
                }

                if (this.prevSortType !== typeObj.type) {
                    this.prevSortType = typeObj.type;
                    this.sortTypes[typeObj.type].classes.push('active-sort');
                    this.sortBy(typeObj.type);
                } else {
                    this.prevSortType = '';
                    this.transactions = this.copyTransactions;
                }
            },

            search($event) {

                let term = $event.target.value.toLowerCase();

                if(!term) {
                    this.transactions = this.copyTransactions;
                } else {
                    let filteredTransactions = this.transactions.filter(t => {
                        let paymentType = t.payment_type.toLowerCase();
                        let sender = t.sender.username.toLowerCase();
                        let receiver = t.receiver.username.toLowerCase();

                        return  paymentType.indexOf(term) >= 0 ||
                            sender.indexOf(term) >= 0 ||
                            receiver.indexOf(term) >= 0 ||
                            t.amount.includes(term);
                    });

                    if (filteredTransactions.length) {
                        this.noSearchResults = false;
                        this.transactions = filteredTransactions;
                    } else {
                        this.noSearchResults = true;
                    }
                }
            },

            sortBy(type) {

                let sortedTransactions = [...this.transactions].sort(this.compare.bind(null, type));
                this.transactions = sortedTransactions;
            },

            compare(type, a, b) {

                let bandA, bandB;
                switch (type) {
                    case 'date':
                        bandA = a.created_at;
                        bandB = b.created_at;
                        break;
                    case 'beneficiant':
                        bandA = a.sender.username.toUpperCase();
                        bandB = b.sender.username.toUpperCase();
                        break;
                    case 'amount':
                        bandA = +a.amount;
                        bandB = +b.amount;
                        break;
                    default:
                        bandA = a.created_at
                        bandB = b.created_at;
                }


                let comparison = 0;
                if (bandA > bandB) {
                    comparison = -1;
                } else if (bandA < bandB) {
                    comparison = 1;
                }
                return comparison;
            },

            getAmount(amount) {
                return `-$${amount}`;
            },

            getTransactions() {
                this.$loading(true);
                this.$http({
                    url: `transactions`,
                    method: 'GET'
                })
                    .then((res) => {
                        this.$loading(false);
                        this.transactions = res.data.data;
                        this.copyTransactions = [...this.transactions];
                    }, () => {
                        this.$loading(false);
                        this.has_error = true
                    })
            }
        }
    }
</script>
<style scoped src="../../assets/styles/styles.css">
</style>
<style>
    .transfer-form {
        float: left;
        margin-right: 50px;
        width: 100%;
        max-width: 20rem;
    }

    .recent-transactions-header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .text-header {
        text-align: center;
        flex-grow: 2
    }

    .sort {
        display: flex
    }

    .sort-types {
        display: inline-flex;
    }

    .sort-types > button:hover {
        background-color: #CABED6;
        border: 1px solid black;
    }

    .sort-types > button {
        border: 1px solid black;
        border-radius: 0
    }

    .sort-types > button:not(:last-child) {
        border-right: none;
    }

    .filters-container {
        display: flex;
        justify-content: space-between;
    }

    .filters-container.form-group {
        margin: 10px 0px 30px 0px
    }

    .active-sort {
        background-color:#767a7d !important;
    }

    .transaction-status-s {
        border-left:10px solid #BC201F;
    }

    .transaction-status-r {
        border-left:10px solid #B59131;
    }

    .transaction-status-c {
        border-left:10px solid #15A27A;
    }
</style>
