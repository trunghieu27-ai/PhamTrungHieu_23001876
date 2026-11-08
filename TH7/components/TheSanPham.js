// Component <the-san-pham> (Yêu cầu 2, 3, 5, 6, 7, 8)
const TheSanPham = {
    // Yêu cầu 2 & 3: Nhận các prop tên, giá, ảnh, mô tả, số lượng tồn; khai báo kiểu dữ liệu, required và default
    props: {
        ten: {
            type: String,
            required: true
        },
        gia: {
            type: Number,
            required: true
        },
        anh: {
            type: String,
            default: 'https://placehold.co/600x400/e0e7ff/3730a3?text=San+Pham'
        },
        moTa: {
            type: String,
            default: 'Chưa có mô tả chi tiết cho sản phẩm này.'
        },
        soLuongTon: {
            type: Number,
            default: 0
        }
    },
    // Yêu cầu 5 & 6: Trạng thái bật/tắt mô tả lưu trong data() riêng của từng component
    data() {
        return {
            hienMoTa: false
        };
    },
    methods: {
        formatCurrency(val) {
            return Number(val).toLocaleString('vi-VN') + ' đồng';
        },
        muaSanPham() {
            if (this.soLuongTon > 0) {
                alert(`Bạn đã chọn mua sản phẩm: "${this.ten}" với giá ${this.formatCurrency(this.gia)}!`);
            }
        }
    },
    template: `
        <div class="product-card" :class="{ 'out-of-stock': soLuongTon <= 0 }">
            <div class="product-image-wrap">
                <img :src="anh" :alt="ten" class="product-image" />
                <!-- Yêu cầu 7: Sản phẩm hết hàng có nhãn riêng -->
                <span v-if="soLuongTon > 0" class="stock-badge in-stock">
                    Còn hàng ({{ soLuongTon }})
                </span>
                <span v-else class="stock-badge out-stock">
                    Hết hàng
                </span>
            </div>

            <div class="product-body">
                <h3 class="product-title">{{ ten }}</h3>
                <div class="product-price">{{ formatCurrency(gia) }}</div>

                <div class="product-actions">
                    <!-- Yêu cầu 5: Nút "Xem mô tả" bật/tắt phần mô tả -->
                    <button
                        type="button"
                        class="btn-desc"
                        @click="hienMoTa = !hienMoTa"
                    >
                        {{ hienMoTa ? 'Ẩn mô tả' : 'Xem mô tả' }}
                    </button>

                    <!-- Yêu cầu 7: Khóa nút "Mua" khi sản phẩm hết hàng -->
                    <button
                        type="button"
                        class="btn-buy"
                        :disabled="soLuongTon <= 0"
                        @click="muaSanPham"
                    >
                        Mua
                    </button>
                </div>

                <!-- Phần mô tả hiển thị độc lập theo trạng thái riêng của từng thẻ -->
                <div v-if="hienMoTa" class="product-desc">
                    <strong>Mô tả:</strong> {{ moTa }}
                </div>
            </div>
        </div>
    `
};

