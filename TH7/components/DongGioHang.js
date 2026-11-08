// Component con <dong-gio-hang> (Yêu cầu 2, 3, 6)
const DongGioHang = {
    // Yêu cầu 2: Nhận dữ liệu bằng props
    props: {
        id: {
            type: Number,
            required: true
        },
        ten: {
            type: String,
            required: true
        },
        donGia: {
            type: Number,
            required: true
        },
        soLuong: {
            type: Number,
            required: true
        }
    },
    // Yêu cầu 3: Khai báo các sự kiện tùy chỉnh phát lên component cha
    emits: ['doi-so-luong', 'yeu-cau-xoa'],
    computed: {
        // Yêu cầu 2: Hiển thị thành tiền
        thanhTien() {
            return this.donGia * this.soLuong;
        }
    },
    methods: {
        // Yêu cầu 3 & 6: Không gán lại props, chỉ phát sự kiện doi-so-luong kèm (id, soLuongMoi)
        giamSoLuong() {
            this.$emit('doi-so-luong', this.id, this.soLuong - 1);
        },
        tangSoLuong() {
            this.$emit('doi-so-luong', this.id, this.soLuong + 1);
        },
        nhapSoLuong(event) {
            const giaTriMoi = Number(event.target.value);
            this.$emit('doi-so-luong', this.id, giaTriMoi);
            // Giữ nguyên giá trị hiển thị từ props cho tới khi cha kiểm tra và cập nhật
            event.target.value = this.soLuong;
        },
        // Yêu cầu 3: Phát sự kiện yeu-cau-xoa gửi mã sản phẩm cần xóa
        xoaSanPham() {
            this.$emit('yeu-cau-xoa', this.id);
        },
        formatCurrency(val) {
            return Number(val).toLocaleString('vi-VN') + ' đồng';
        }
    },
    template: `
        <div class="cart-row">
            <div class="col-id">#{{ id }}</div>
            <div class="col-name"><strong>{{ ten }}</strong></div>
            <div class="col-price">{{ formatCurrency(donGia) }}</div>
            <div class="col-qty">
                <div class="qty-controls">
                    <button
                        type="button"
                        class="btn-qty"
                        @click="giamSoLuong"
                        :disabled="soLuong <= 1"
                    >-</button>
                    <input
                        type="number"
                        class="qty-input"
                        :value="soLuong"
                        min="1"
                        max="99"
                        @change="nhapSoLuong"
                    />
                    <button
                        type="button"
                        class="btn-qty"
                        @click="tangSoLuong"
                        :disabled="soLuong >= 99"
                    >+</button>
                </div>
            </div>
            <div class="col-subtotal">{{ formatCurrency(thanhTien) }}</div>
            <div class="col-action">
                <button type="button" class="btn-remove" @click="xoaSanPham">
                    Xóa
                </button>
            </div>
        </div>
    `
};
