<template>
    <div>
        <h1>{{ name }}</h1>
        <div style="margin-bottom: 1rem;">
            <el-button size="small" @click="goBack"><el-icon name="back"></el-icon> Back</el-button>
        </div>

        <el-table
            :data="rates"
            v-loading="loading"
            style="width: 100%"
        >
            <el-table-column
                prop="nominal"
                label="Nominal"
            />
            <el-table-column
                prop="value"
                label="Rate"
            />
            <el-table-column
                prop="date"
                label="Date"
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
            name: 'Currency',
            num_code: 0,
            loading: false,
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
    mounted() {
        let params = new URLSearchParams(location.search)
        this.num_code = parseInt(params.get("num_code"))
        this.fetchRates()
    },
    methods: {
        fetchRates() {
            this.loading = true

            axios.get(`/api/rate/${this.num_code}`, {
                params: {
                    page: this.pagination.current_page,
                    per_page: this.pagination.per_page,
                }
            })
                .then(({ data }) => {
                    this.pagination = {
                        per_page: +data.rates.per_page,
                        current_page: +data.rates.current_page,
                        total: +data.rates.total,
                    }

                    this.rates = data.rates.data

                    this.name = data.name
                })
                .finally(() => {
                    this.loading = false
                })
        },
        setCurrentPage(page, force = false) {
            if (this.pagination.current_page === page && !force) return

            this.pagination.current_page = page
            this.fetchRates()
        },
        onPerPageChange() {
            this.setCurrentPage(1, true)
        },
        goBack() {
            history.back()
        },
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
</style>
