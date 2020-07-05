var app = new Vue({
    el: '#app',
    data: {
        errors: [],
        q: null,
        isActive: false
    },
    methods: {
        checkForm: function (e) {
            this.q = this.q.trim();
            if (this.q) {
                this.isActive = !this.isActive;
                return true;
            }

            this.errors = [];

            if (!this.q) {
                this.errors.push('Search Text Required!');
            }

            e.preventDefault();
        }
    }
})