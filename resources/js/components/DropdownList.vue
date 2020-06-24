<template>
    <div class="dropdown show">
        <div :class="{'dropdown-menu': true, 'show': openSuggestion}">
            <a v-for="(suggestion, index) in matches" class="dropdown-item"
               @click="suggestionClick(index)" href="#">{{ suggestion }}</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['config'],
        data() {
            return {
            }
        },
        created() {
        },
        computed: {
            openSuggestion() {
                return this.config.selection !== "" &&
                    this.matches.length != 0 &&
                    this.config.open === true;
            },

            matches() {
                return this.config.users.filter((str) => {
                    return str.toLowerCase().indexOf(this.config.selection.toLowerCase()) >= 0;
                });
            },
        },
        methods: {

            suggestionClick(index) {
                this.$emit('suggestionClicked', this.matches[index])
            },
        }
    }
</script>
