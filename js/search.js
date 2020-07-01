var app = new Vue({
    el: '#app',
    data: {
        errors: [],
        q: window.myDataFromPhp['q'],
        isActive: false
    },
    methods: {
        trimremove: function () {
            this.q = this.q.trim();
        },
        checkForm: function (e) {
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