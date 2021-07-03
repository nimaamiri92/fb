<style>
html {
    font-family: IranYekan !important;
}

.hovering {
    cursor: pointer;
}

.selected_row {
    background-color: yellow;
}

.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
    display: table;
    transition: opacity .3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.close {
    position: absolute;
    left: 20px;
    color: darkred;
}
</style>
<template>

    <section class="content">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body" style="overflow-x: scroll">
                <!-- SEARCH FORM -->
                <div class="row">
                    <div class="form-inline col-md-3">
                        <div class="input-group">
                            <input v-on:keyup.enter="doSearch" v-on:keydown.enter="doSearch" type="text"
                                   v-model="search" class="form-control">

                        </div>
                    </div>
                    <div class="form-inline col-md-2">
                        <label for="order_state">وضعیت سفارش: </label>
                        <div id="order_state" style="margin: 5px">
                            <Select2 style="width: 150px" v-model="order_state_filter"
                                     :options="order_state_filter_option"
                                     :settings="{ multiple: false,'width': '100%','scrollAfterSelect':true }"
                                     @change="orderFilter()"/>
                        </div>
                    </div>
                    <div class="form-inline col-md-2">
                        <label for="payment_status">وضعیت پرداخت: </label>
                        <div id="payment_status" style="margin: 5px">
                            <Select2 style="width: 150px" v-model="payment_status_filter"
                                     :options="payment_status_filter_option"
                                     :settings="{ multiple: false,'width': '100%','scrollAfterSelect':true }"
                                     @change="paymentFilter()"/>
                        </div>
                    </div>
                </div>
                <br>
                <loading :active.sync="isLoading"
                         :can-cancel="true"
                         :is-full-page="fullPage"></loading>
                <table id="example1" class="table table-bordered table-striped" style="overflow-x: scroll">
                    <thead>
                    <tr>
                        <th>شناسه سفارش</th>
                        <th>نام گیرنده</th>
                        <th>نام سفارش دهنده</th>
                        <th> مبلغ سفارش</th>
                        <th>وضعیت سفارش</th>
                        <th>وضعیت پرداخت</th>
                        <th> تاریخ سفارش</th>
                    </tr>
                    </thead>

                    <tr v-for="(order,key) in listOfOrders"
                        :style="{backgroundColor: order.trColor}"
                        @mouseover="isHovering = true"
                        @mouseout="isHovering = false"
                        :class="{hovering: isHovering}"
                        :key="order.id">
                        <td v-on:click="openOrder(order.id)" data-toggle="modal" data-target="#exampleModalScrollable">
                            {{ "Ref. " + order.id }}
                        </td>
                        <td v-on:click="openOrder(order.id)"  data-toggle="modal" data-target="#exampleModalScrollable">{{ order.name_of_receiver }}</td>
                        <td v-on:click="openOrder(order.id)"  data-toggle="modal" data-target="#exampleModalScrollable">{{ order.user.name }}</td>
                        <td v-on:click="openOrder(order.id)"  data-toggle="modal" data-target="#exampleModalScrollable">{{ order.total_order_price }}</td>
                        <td v-on:click="openOrder(order.id)"  data-toggle="modal" data-target="#exampleModalScrollable" :style="{backgroundColor:order.tdColor}">
                            <span class="badge" :class="order.order_state_badge">{{ order.order_state }}</span>
                        </td>
                        <td v-on:click="openOrder(order.id)"  data-toggle="modal" data-target="#exampleModalScrollable">
                            <span>
                                <status-indicator :status="order.icon" :pulse="true"/>
                                {{ order.payment_status }}
                            </span>
                        </td>
                        <td v-on:click="openOrder(order.id)"  data-toggle="modal" data-target="#exampleModalScrollable">
                            {{ order.created_at | moment("jYYYY/jM/jD") }}
                        </td>
                    </tr>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <pagination style="margin: 20px 20px;" :data="orders"
                        @pagination-change-page="callListOfOrderApi"></pagination>
        </div>


        <!--  Invoice PDF   -->
        <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog"  style="z-index: 100000"
             aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Ref. {{ this.show_order.id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            <button @click="downloadPdf(show_order.id)" class="btn btn-primary" style="float: left">
                                دانلود فاکتور
                            </button>
                            <button v-on:click="rejectOrder([show_order.id])" type="button" class="btn btn-danger">رد سفارش</button>
                            <button v-on:click="approvedOrders([show_order.id])" type="button" class="btn btn-success">تایید سفارش</button>
                        </p>
                        <p style="font-size: 20px;">سفارشات</p>
                        <p style="font-size: 15px; color: grey">{{ this.show_order.name_of_receiver}}</p>
                        <table class="table table-bordered" style="margin-bottom: 35px;">
                            <tbody>
                            <tr>
                                <th style="width: 10px">ردیف</th>
                                <th>نام کالا</th>
                                <th>سایز</th>
                                <th>تعداد</th>
                                <th>مبلغ</th>
                                <th>مبلغ نهایی</th>
                            </tr>
                            <tr v-for="(item,index) in this.show_order.order_items">
                                <td>{{ index + 1 }}</td>
                                <td>{{ item.product_name }}</td>
                                <td>{{ item.product_size }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ item.one_product_price_display }}</td>
                                <td>{{ item.all_quantity_of_one_product_price_display }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" rowspan="3"></td>
                                <td class="text-bold">جمع مبالغ</td>
                                <td>{{ show_order.total_price }}</td>
                                <td>{{ show_order.total_final_price }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold" colspan="2">هزینه ارسال</td>
                                <td colspan="3">{{ show_order.shipment_type_price }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold" colspan="2">مبلغ پرداختی</td>
                                <td colspan="3">{{ show_order.user_payment_amount }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <p style="font-size: 20px;">اطلاعات ارسال</p>
                        <table class="table table-bordered" style="margin-bottom: 35px;">
                            <tbody>
                            <tr>
                                <td style="width: 100px;" class="text-bold">نام گیرنده</td>
                                <td colspan="3">{{ show_order.name_of_receiver }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">موبایل</td>
                                <td>{{ show_order.phone }}</td>
                                <td class="text-bold">موبایل ۲</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-bold">استان</td>
                                <td>{{ show_order.province }}</td>
                                <td class="text-bold">شهر</td>
                                <td>{{ show_order.city }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">آدرس</td>
                                <td colspan="3">{{ show_order.address }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">نحوه ارسال</td>
                                <td>{{ show_order.shipment_type_name }}</td>
                                <td class="text-bold">زمان ارسال</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>

                        <p style="font-size: 20px;">اطلاعات پرداخت</p>
                        <table class="table table-bordered" style="margin-bottom: 35px;">
                            <tbody>
                            <tr>
                                <th style="width: 10px">ردیف</th>
                                <th>نام درگاه</th>
                                <th>شماره پیگیری</th>
                                <th>نتیجه پرداخت</th>
                                <th>مبلغ</th>
                            </tr>
                            <tr v-for="(item,index) in this.show_order.payments">
                                <td>{{ index + 1 }}</td>
                                <td>{{ item.bank_name }}</td>
                                <td>{{ item.sale_reference_id }}</td>
                                <td>{{ item.res_string }}</td>
                                <td>{{ item.total_order_price }}</td>
                            </tr>
                            </tbody>
                        </table>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">بستن</button>
                        <button v-on:click="rejectOrder([show_order.id])" type="button" class="btn btn-danger">رد سفارش</button>
                        <button v-on:click="approvedOrders([show_order.id])" type="button" class="btn btn-success">تایید سفارش</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<!--f0568d-->
<script>
export default {
    data: function () {
        return {
            order_state_filter: '',
            order_state_filter_option: [],
            payment_status_filter: '',
            payment_status_filter_option: [],
            showModal: false,
            single_order_data: {},
            search: '',
            orders: {},
            show_order: {},
            listOfOrders: {},
            payment_statuses: {
                "NOT_PAID": {
                    'icon': 'negative',
                    'color': '#838784',
                    'text': 'پرداخت نشده',
                },
                "FAIL_PAYMENT": {
                    'icon': 'negative',
                    'color': '#838784',
                    'text': 'ناموفق',
                },
                "SUCCESSFUL_PAYMENT": {
                    'icon': 'positive',
                    'text': 'موفق',
                },
                "UNKNOWN_PAYMENT": {
                    'icon': 'intermediary',
                    'color': '#838784',
                    'text': 'وضعیت نامشخص',
                }
            },
            order_states: {
                "REJECTED": {
                    'badge': 'bg-danger',
                    'text': 'رد شده',
                },
                "APPROVED": {
                    'badge': 'bg-success',
                    'text': 'تایید شده',
                },
                "IN_PROGRESS": {
                    'badge': 'bg-warning',
                    'text': 'در انتظار تایید',
                }
            },
            selected_row: [],
            isLoading: false,
            fullPage: true,
            isHovering: false,
            open: false
        }
    },
    mounted() {
        this.callListOfOrderApi()
        this.setOrderFilters()
        this.setPaymentFilters()
    },
    methods: {
        paymentStatusFilterEvent(val) {
            console.log(val);
        },
        toPersianNumberFormat(x) {
            return x.toLocaleString('fa-IR').replace(/\٬/g, ",");
        },
        callListOfOrderApi(page = 1, sort = 'id', sort_type = 'DESC') {
            this.isLoading = true;
            let url = 'orders?page=' + page
                + '&sort=' + (sort_type === 'DESC' ? '+' + sort : '-' + sort());


            if (this.search !== null && this.search !== '') {
                url += '&search=' + this.search
            }
            if (this.order_state_filter !== null && this.order_state_filter !== '') {
                url += '&order_state=' + this.order_state_filter
            }
            if (this.payment_status_filter !== null && this.payment_status_filter !== '') {
                url += '&payment_status=' + this.payment_status_filter
            }

            let self = this
            axios
                .get(url)
                .then(response => {
                    self.orders = response.data;
                    response.data.data.forEach((order) => {
                        var reserved_order_state = self.order_states[order.order_state]
                        var reserved_payment_statuses = self.payment_statuses[order.payment_status]
                        order.order_state = reserved_order_state.text
                        order.order_state_badge = reserved_order_state.badge

                        order.payment_status = reserved_payment_statuses.text

                        Vue.set(order, 'icon', reserved_payment_statuses.icon)

                    })

                    self.listOfOrders = self.orders.data
                    self.isLoading = false;
                });
        },
        orderFilter() {
            this.callListOfOrderApi(1, 'id', 'DESC')
        },
        paymentFilter() {
            this.callListOfOrderApi(1, 'id', 'DESC')
        },
        setOrderFilters() {
            this.order_state_filter_option = [
                {
                    "id": "EMPTY",
                    "text": 'انتخاب کنید'
                },
                {
                    "id": "APPROVED",
                    "text": this.order_states.APPROVED.text
                },
                {
                    "id": "IN_PROGRESS",
                    "text": this.order_states.IN_PROGRESS.text
                },
                {
                    "id": "REJECTED",
                    "text": this.order_states.REJECTED.text
                }
            ]
        },
        setPaymentFilters() {
            this.payment_status_filter_option = [
                {
                    "id": "EMPTY",
                    "text": 'انتخاب کنید'
                },
                {
                    "id": "FAIL_PAYMENT",
                    "text": this.payment_statuses.FAIL_PAYMENT.text
                },
                {
                    "id": "NOT_PAID",
                    "text": this.payment_statuses.NOT_PAID.text
                },
                {
                    "id": "SUCCESSFUL_PAYMENT",
                    "text": this.payment_statuses.SUCCESSFUL_PAYMENT.text
                },
                {
                    "id": "UNKNOWN_PAYMENT",
                    "text": this.payment_statuses.UNKNOWN_PAYMENT.text
                }
            ]
        },
        log(item) {
            console.log(item)
        },
        openOrder(order_id) {
            let url = '/orders/' + order_id
            let self = this;
            this.showModal = true;
            axios
                .get(url)
                .then(response => {
                    let total_price = 0;
                    let total_final_price = 0;
                    let user_payment_amount = 0;
                    self.show_order = response.data
                    self.show_order.order_items.forEach((order_items, index) => {
                        total_price += Number(order_items.product_price);
                        total_final_price += Number(order_items.product_price * order_items.quantity)
                        self.show_order.order_items[index].one_product_price_display = self.toPersianNumberFormat(Number(order_items.product_price))
                        self.show_order.order_items[index].all_quantity_of_one_product_price_display = self.toPersianNumberFormat(Number(order_items.product_price * order_items.quantity))
                    });

                    self.show_order.payments.forEach((payment, index) => {
                        if (payment.res_code === 0) {
                            user_payment_amount = payment.total_order_price
                        }
                        self.show_order.payments[index].total_order_price = self.toPersianNumberFormat(Number(payment.total_order_price))
                    });

                    self.show_order.total_price = self.toPersianNumberFormat(total_price)
                    self.show_order.total_final_price = self.toPersianNumberFormat(total_final_price)
                    self.show_order.shipment_type_price = self.toPersianNumberFormat(Number(self.show_order.shipment_type_price))
                    self.show_order.user_payment_amount = self.toPersianNumberFormat(Number(user_payment_amount))
                });


            // console.log(this.show_order)
        },
        selectOrder(order) {
            if (!this.selected_row.includes(order.id)) {
                this.selected_row.push(order.id)
                Vue.set(order, 'preTrColor', order.trColor)
                order.trColor = '#abadb3'
            } else {
                const index = this.selected_row.indexOf(order.id);
                if (index > -1) {
                    this.selected_row.splice(index, 1);
                }
                order.trColor = ''
            }
        },
        downloadPdf(order_id) {
            let url = '/orders/' + order_id + '/download-invoice'
            axios(url, {
                method: 'GET',
                responseType: 'blob'
            })
                .then(response => {
                    //Create a Blob from the PDF Stream
                    const file = new Blob(
                        [response.data],
                        {type: 'application/pdf'});
                    //Build a URL from the file
                    const fileURL = URL.createObjectURL(file);
                    //Open the URL on new Window
                    window.open(fileURL);
                })
                .catch(error => {
                    console.log(error);
                });

        },
        doSearch() {
            this.callListOfOrderApi(1, this.search)
        },
        setOrdersAsPaid() {

        },
        toggle() {
            this.open = !this.open;
        },
        approvedOrders(ids =null) {

            if (ids === null) {
                ids = this.selected_row
            }

            let url = 'orders/approved';
            let self = this
            axios
                .post(url, {
                    "orders": ids
                })

            location.reload();
        },
        rejectOrder(ids =null) {

            if (ids === null) {
                ids = this.selected_row
            }
            let url = 'orders/rejectOrder';
            let self = this
            axios
                .post(url, {
                    "orders": ids
                })

            location.reload();
        }
    }
}
</script>
