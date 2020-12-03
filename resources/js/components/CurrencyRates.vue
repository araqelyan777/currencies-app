<template>
    <div>
        <h1>Currency rates</h1>
        <div style="margin-bottom: 1rem;">
            <el-date-picker
                v-model="date"
                type="date"
                placeholder="Pick a day"
                size="small"
                value-format="yyyy-MM-dd"
            />
        </div>

        <el-table
            :data="rates"
            v-loading="loading"
            style="width: 100%"
            stripe
            row-class-name="clickable"
            @row-click="onRateClick"
        >
            <el-table-column
                prop="name"
                label="Name"
                width="180"
            />
            <el-table-column
                prop="num_code"
                label="Code"
                width="180"
            />
            <el-table-column
                prop="nominal"
                label="Nominal"
            />
            <el-table-column
                prop="value"
                label="Rate"
            />
        </el-table>

        <div class="pagination-container">
            <el-pagination
                background
                layout="prev, pager, next"
                :currentPage="pagination.current_page"
                @update:currentPage="setCurrentPage"
                :total="pagination.total"
                :page-size="pagination.per_page"
            />

            <el-select size="small" v-model="pagination.per_page" @input="onPerPageChange">
                <el-option
                    v-for="item in per_page_values"
                    :key="item"
                    :label="item"
                    :value="item"
                />
            </el-select>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            date: null,
            pagination: {
                current_page: 1,
                per_page: 5,
                total: 0,
            },
            rates: [],
            per_page_values: [
                5,
                10,
                15,
                25,
                50,
            ],
        }
    },
    watch: {
        date() {
            this.fetchRates()
        },
    },
    methods: {
        fetchRates() {
            this.loading = true

            axios.get('/api/currency-rates', {
                params: {
                    page: this.pagination.current_page,
                    per_page: this.pagination.per_page,
                    date: this.date,
                }
            })
                .then(({ data }) => {
                    this.pagination = {
                        per_page: +data.per_page,
                        current_page: +data.current_page,
                        total: +data.total,
                    }

                    this.rates = data.data
                })
                .finally(() => {
                    this.loading = false
                })
        },
        setCurrentPage(page) {
            this.pagination.current_page = page
            this.fetchRates()
        },
        onPerPageChange() {
            this.setCurrentPage(1)
        },
        onRateClick(data) {
            location.href = `/rate?num_code=${data.num_code}`
        },
        onDateInput(date) {
            console.log(date)
        }
    },
}
</script>

<style>
.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.clickable {
    cursor: pointer;
}
</style>
