import {reactive} from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import {isSet} from 'lodash';
import {debounce} from 'lodash';

const appUrl = window?.Laravel?.appUrl || '';

const pathID = window.location.href.replace(`${appUrl}/branch/`, "").replace("/pos", "");
const data = {id: pathID};

const old = localStorage.getItem('cartsessionid');
if (old == null) {
    const random = new Date().valueOf();
    localStorage.setItem('cartsessionid', random);
}
export const store = reactive({
    count: 0,
    customer: null,
    orderid: null,
    customers: {
        phone: '',
        email: '',
        name: '',
        address: '',
    },
    tax: 0,
    products: [],
    items: [],
    subtotal: 0,
    discount: 0,
    total: 0,
    paid: 0,
    due: 0,
    grandTotal: 0,
    customeVolume: 0,
    customePrice: 0,
    customeError: "",
    orderError: "",
    paymentmethod: 'cod',
    transactionid: null,
    extradiscount: 0,
    show: false,
    posts: [],
    holdorders: [],
    showproductholdorder: false,
    holdorderid: null,
    editholdorderid: null,
    holdorderproduct: [],
    searchbyname: null,
    searchbybarcode: null,
    qrname: true,
    scantemp: false,
    setcartone: true,
    observer: null,
    isContrastActive: false,
    invoice: null,
    watch: {
        '$store.state.list': {
            handler() {
                // console.log("OP")
            },
            immediate: true
        }
    },
    getorderId() {
        axios.get(appUrl + '/api/v1/getorderid').then((response) => {
            // console.log("order", response.data.data)
            this.orderid = response.data.data
        })
    },
    activateObserver() {
        this.observer = new IntersectionObserver(
            ([entry]) => {
                if (!entry.isIntersecting) {
                    this.isContrastActive = true;
                } else {
                    this.isContrastActive = false;
                }
            },
            {rootMargin: "-5% 0px 0px 0px"}
        );
        document
            .querySelectorAll(".observer")
            .forEach((el) => this.observer.observe(el));
    },
    createdss() {
        axios.post(appUrl + '/api/v1/getproducts', data).then((response) => {
            this.products = response.data.data
        })
        axios.post(appUrl + '/api/v1/getcatpos', data).then((response) => {
            this.posts = response.data.data
        })

        this.getCart();
    },
    showaddcustomer() {
        document.getElementById('addcustomermodal').setAttribute("style", "display:block");
    },
    hidemodalss() {
        document.getElementById('addcustomermodal').setAttribute("style", "display:none");
    },
    qusearch() {
        // console.log(this.qrname)
        this.qrname = !this.qrname
        this.scantemp = !this.scantemp
    },
    queryForKeywords: debounce(function (event) {
        const value = event.target.value;
        const bid = localStorage.getItem('bid');
        const data = {phone: value, bid: bid};

        axios.post(appUrl + '/api/v1/getcustomer', data).then((response) => {
            if (response.data.data) {
                this.customer = response.data.data;
                this.customers.phone = response.data.data.phone;
                this.customers.email = response.data.data.email;
                this.customers.name = response.data.data.name;
                this.customers.address = response.data.data.address;
            } else {
                this.customer = null;
                this.customers.phone = event.target.value;
                this.customers.email = '';
                this.customers.name = '';
                this.customers.address = '';
            }
        });
    }, 300),
    cusemail(event) {
        this.customers.email = event.target.value
    },
    cusname(event) {
        this.customers.name = event.target.value
    },
    cusaddress(event) {
        this.customers.address = event.target.value
    },
    savecustomer() {
        if (this.customers.phone == '') {
            document.getElementById('addcustomermodal').setAttribute("style", "display:block");
        } else {
            this.customer = this.customers
            document.getElementById('addcustomermodal').setAttribute("style", "display:none");
        }
    },
    savecustomerAndOrder() {
        if (this.customers.phone == '') {
            document.getElementById('addcustomermodal').setAttribute("style", "display:block");
        } else {
            this.customer = this.customers
            document.getElementById('addcustomermodal').setAttribute("style", "display:none");
            this.placeorderss();
        }
    },
    searchbyproductname(event) {
        var listss, indexss;
        listss = document.getElementsByClassName("modal");
        for (indexss = 0; indexss < listss.length; ++indexss) {
            listss[indexss].setAttribute("style", "display:none");
        }
        const name = event.target.value
        const data = {name: name, id: pathID}
        axios.post(appUrl + '/api/v1/getsearchproduct', data).then((response) => {
            this.products = response.data.data
            if (name == '') {

            } else {

                if (response.data.data[0].vr == 1) {
                    this.openmodal(`exampleModals` + response.data.data[0].id)
                } else {
                    if (this.setcartone) {
                        this.addtocart(response.data.data[0].id);
                        this.setcartone = false
                    } else {
                        this.setcartone = true
                    }
                }
            }
        })

    },
    searchbyproductbarcode(event) {
        const name = event.target.value
        const data = {name: name, id: pathID}
        axios.post(appUrl + '/api/v1/getsearchproductbarcode', data).then((response) => {
            // console.log(response.data.data);
            this.products = response.data.data
        })

    },
    cancelmodal() {
        this.customer = null
        this.customers.phone = ''
        this.customers.email = ''
        this.customers.name = ''
        this.customers.address = ''
        document.getElementById('addcustomermodal').setAttribute("style", "display:none");
    },
    allproduct() {
        axios.post(appUrl + '/api/v1/getproducts', data).then((response) => {
            // console.log(response.data.data)
            this.products = response.data.data
        })
    },
    addtocart(id) {
        const sessions = localStorage.getItem('cartsessionid');
        const bid = localStorage.getItem('bid');
        const data2 = {id: id, session: sessions, bid: bid}
        axios.post(appUrl + '/api/v1/addtocart', data2).then((response) => {
            this.getCart();
        });
    },
    addvericart(id, divid) {
        const sessions = localStorage.getItem('cartsessionid');
        const bid = localStorage.getItem('bid');
        const data2 = {id: id, session: sessions, bid: bid}
        axios.post(appUrl + '/api/v1/addveritocart', data2).then((response) => {
            // console.log(response.data)
            if (response.data.status) {
                document.getElementById(divid).setAttribute("style", "display:none");
                this.getCart();
            }
        });
    },
    addCustomeVericart(id, divid) {
        this.customeError = "";
        if (this.customeVolume == 0 || this.customePrice == 0) {
            this.customeError = "Please enter volume and price";
        } else {
            const sessions = localStorage.getItem('cartsessionid');
            const bid = localStorage.getItem('bid');

            const productPrice = parseFloat(parseFloat(this.customeVolume) * parseFloat(this.customePrice));
            const data2 = {
                id: id,
                session: sessions,
                bid: bid,
                volume: this.customeVolume,
                price: productPrice,
                customeOrder: 1
            }

            axios.post(appUrl + '/api/v1/addveritocart', data2).then((response) => {
                if (!response.data.status) {
                    this.customeError = response.data.message || "Something wrong. Please try again";
                }
                if (response.data.status) {
                    this.customeVolume = 0;
                    this.customePrice = 0;
                    this.customeError = "";

                    document.getElementById(divid).setAttribute("style", "display:none");
                    this.getCart();
                }
            });
        }
    },
    getCart() {
        const sessions = localStorage.getItem('cartsessionid');
        const bid = localStorage.getItem('bid');
        const data1 = {session: sessions, bid: bid}
        axios.post(appUrl + '/api/v1/getcarts', data1).then((response) => {
            this.items = response.data.data;
            this.subtotal = parseFloat(response.data.subtotal);
            this.total = parseFloat(response.data.total);
            this.discount = parseFloat(response.data.discount);
            this.tax = parseFloat(response.data.tax);
            const grandTotal = (parseFloat(response.data.subtotal) + parseFloat(response.data.tax)) - parseFloat(response.data.discount);
            this.grandTotal = grandTotal;
            this.paid = grandTotal;
            this.due = 0;
        })
    },
    increaseValue(id) {
        const sessions = localStorage.getItem('cartsessionid');
        const data2 = {id: id, session: sessions}
        // console.log(data2)
        axios.post(appUrl + '/api/v1/incrementcart', data2).then((response) => {
            this.getCart();
        });
    },
    decreaseValue(id) {
        const sessions = localStorage.getItem('cartsessionid');
        const data2 = {id: id, session: sessions}
        axios.post(appUrl + '/api/v1/decrementcart', data2).then((response) => {
            this.getCart();
        });
    },
    removeItem(id) {
        const data2 = {id: id}
        axios.post(appUrl + '/api/v1/removecart', data2).then((response) => {
            this.getCart();
        });
    },
    onDecode(result) {
        // console.log("decodess", result)
        const name = result
        const data = {name: name, id: pathID}
        axios.post(appUrl + '/api/v1/getcodeproduct', data).then((response) => {
            if (name == '') {
            } else {
                if (response.data.data != null) {
                    store.scantemp = false
                    // console.log(response.data.data[0].vr);
                    store.products = response.data.data
                    if (response.data.data[0].vr == 1) {
                        // store.openmodal(`exampleModals`+response.data.data[0].id)
                        document.getElementById(`exampleModals` + response.data.data[0].id).setAttribute("style", "display:block");
                    } else {
                        if (store.setcartone) {
                            store.addtocart(response.data.data[0].id);
                            store.setcartone = false
                        } else {
                            store.setcartone = true
                        }
                    }
                }
            }
        })
    },
    openmodal(id) {
        const dd = document.getElementById(id);
        document.getElementById(id).setAttribute("style", "display:block");
    },
    hidemodal(id) {
        document.getElementById(id).setAttribute("style", "display:none");
    },
    searchproduct(id) {
        const bid = localStorage.getItem('bid');
        const data2 = {id: id, bid: bid}
        axios.post(appUrl + '/api/v1/getcatproduct', data2).then((response) => {
            this.products = response.data.data
        });
    },
    placeorderss() {
        document.getElementById('checkoutmodal').setAttribute("style", "display:block");
        // if (this.due == 0) {
        //     this.due = this.total
        // }
    },
    hidecheckout() {
        this.orderError = "";
        document.getElementById('checkoutmodal').setAttribute("style", "display:none");
    },
    extradiscountss(event) {
        let extradiscount = parseFloat(event.target.value) || 0;
        extradiscount = this.total < extradiscount ? this.total : extradiscount;

        this.extradiscount = extradiscount || 0;
        this.grandTotal = (this.subtotal + this.tax) - (this.discount + extradiscount);
        const dueAmount = this.grandTotal - this.paid;
        this.due = dueAmount < 0 ? 0 : dueAmount;
    },
    paidss(event) {
        let paidAmount = parseFloat(event.target.value) || 0;
        paidAmount = this.grandTotal < paidAmount ? this.grandTotal : paidAmount;

        this.paid = paidAmount || 0;
        let newDue = this.grandTotal - paidAmount;
        this.due = newDue < 0 ? 0 : newDue;
    },
    placeorder() {
        this.orderError = "";
        const bid = localStorage.getItem('bid');
        const datasd = {
            customer: this.customers,
            items: this.items,
            subtotal: this.subtotal,
            discount: this.discount,
            total: this.total,
            bid: bid,
            payment_type: 'cod',
            session: localStorage.getItem('cartsessionid'),
            holdorderid: this.holdorderid,
            tax: this.tax,
            paid: this.paid,
            due: this.due,
            paymentmethod: this.paymentmethod,
            transactionid: this.transactionid,
            extradiscount: this.extradiscount,
        }

        axios.post(appUrl + '/api/v1/posorder', datasd).then((response) => {
            if (response.data.status) {
                this.resetState();

                document.getElementById('checkoutmodal').setAttribute("style", "display:none");
            } else {
                this.orderError = response.data.message || "Something wrong. Please try again!";
            }
        });
    },
    placeorderWithPrint() {
        this.orderError = "";
        const bid = localStorage.getItem('bid');
        const datasd = {
            customer: this.customers,
            items: this.items,
            subtotal: this.subtotal,
            discount: this.discount,
            total: this.total,
            bid: bid,
            payment_type: 'cod',
            session: localStorage.getItem('cartsessionid'),
            holdorderid: this.holdorderid,
            tax: this.tax,
            paid: this.paid,
            due: this.due,
            paymentmethod: this.paymentmethod,
            transactionid: this.transactionid,
            extradiscount: this.extradiscount,
        }

        axios.post(appUrl + '/api/v1/posorder', datasd).then((response) => {
            if (response.data.status) {
                this.invoice = response?.data?.data;
                this.resetState();

                document.getElementById('checkoutmodal').setAttribute("style", "display:none");
                document.getElementById('invoiceModal').setAttribute("style", "display:block");
            } else {
                this.orderError = response.data.message || "Something wrong. Please try again!";
            }
        });
    },
    resetState() {
        this.customer = null;
        this.customers.phone = ''
        this.customers.email = ''
        this.customers.name = ''
        this.customers.address = ''
        this.items = []
        this.subtotal = 0
        this.discount = 0
        this.total = 0;
        this.holdorderid = null
        this.paid = 0
        this.due = 0
        this.extradiscount = 0
        this.paymentmethod = 'cod',
            this.transactionid = null,
            localStorage.removeItem('cartsessionid');
        const random = new Date().valueOf();
        localStorage.setItem('cartsessionid', random);
    },
    online() {
        document.getElementById('online').setAttribute("style", "background-color:#dc3545 !important;height:100px;width:100px");
        document.getElementById('cod').setAttribute("style", "background-color:#212529 !important;height:100px;width:100px");
        this.paymentmethod = 'online'
    },
    cod() {
        document.getElementById('cod').setAttribute("style", "background-color:#dc3545 !important;height:100px;width:100px");
        document.getElementById('online').setAttribute("style", "background-color:#212529 !important;height:100px;width:100px");
        this.paymentmethod = 'cod'
    },
    transactionids(event) {
        const value = event.target.value
        this.transactionid = value
    },
    showholdorder() {
        this.getholforder()
        document.getElementById('showholdordermodel').setAttribute("style", "display:block");
    },
    hideholdorder() {
        this.showproductholdorder = false,
            this.holdorderproduct = [],
            this.holdorderid = null
        document.getElementById('showholdordermodel').setAttribute("style", "display:none");
    },
    showkeyboard() {
        this.getholforder()
        document.getElementById('showkeyboardmodel').setAttribute("style", "display:block");
    },
    hidekeyboard() {
        document.getElementById('showkeyboardmodel').setAttribute("style", "display:none");
    },
    getholforder() {
        axios.post(appUrl + '/api/v1/getholdorders', data).then((response) => {
            this.holdorders = response.data.data
        })
    },
    holdorder() {
        const bid = localStorage.getItem('bid');
        const datasd = {
            items: this.items,
            subtotal: this.subtotal,
            discount: this.discount,
            total: this.total,
            bid: bid,
            payment_type: 'cod',
            session: localStorage.getItem('cartsessionid'),
            holdorderid: this.editholdorderid
        }
        axios.post(appUrl + '/api/v1/posorderhold', datasd).then((response) => {
            if (response.data.message == 'success') {
                this.customer = null;
                this.customers.phone = ''
                this.customers.email = ''
                this.customers.name = ''
                this.customers.address = ''
                this.items = []
                this.subtotal = 0
                this.discount = 0
                this.total = 0;
                this.holdorderid = null;
                this.editholdorderid = null;
                localStorage.removeItem('cartsessionid');
                const random = new Date().valueOf();
                localStorage.setItem('cartsessionid', random);
            }
        });
    },
    showholdorders(id) {
        const data = {id: id};
        axios.post(appUrl + '/api/v1/holdorderproduct', data).then((response) => {
            this.holdorderproduct = response.data.data
            this.showproductholdorder = true
            this.holdorderid = id
        })
    },
    deleteholdorder(id) {
        const data = {id: id};
        axios.post(appUrl + '/api/v1/deleteholdorder', data).then((response) => {
            this.holdorderproduct = []
            this.showproductholdorder = false
            this.holdorderid = null
            this.getholforder()
        })
    },
    editholdorder(id) {
        const data = {id: id};
        axios.post(appUrl + '/api/v1/editholdorders', data).then((response) => {
            localStorage.setItem('cartsessionid', response.data.session)
            // this.customers.email = response.data.user.email
            // this.customers.phone = response.data.user.phone
            // this.customers.name = response.data.user.name
            // this.customers.address = response.data.user.address
            // this.customer=response.data.user
            this.getCart();
            this.showproductholdorder = false,
                this.holdorderproduct = [],
                this.holdorderid = id
            this.editholdorderid = id
            document.getElementById('showholdordermodel').setAttribute("style", "display:none");
        })
    },
    allhidemodal() {
        var listss, indexss;
        listss = document.getElementsByClassName("modal");
        for (indexss = 0; indexss < listss.length; ++indexss) {
            listss[indexss].setAttribute("style", "display:none");
        }
    }
})
