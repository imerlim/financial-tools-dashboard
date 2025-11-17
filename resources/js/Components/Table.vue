<template>
    <div class="bg-slate-100 dark:bg-slate-900 dark:text-white rounded-md">
        <!-- Campo de busca e itens por página -->
        <div v-if="!loading">
            <div v-if="showSearch" class="grid md:grid-cols-6 mb-4 sm:justify-between gap-4">
                <div class="sm:col-span-3">
                    <CustomInput class="mt-7" v-model="searchQuery" type="text" placeholder="Buscar..."></CustomInput>
                </div>
                <div class="sm:col-span-3 sm:col-start-6">
                    <CustomSelect
                        label="Itens por página:"
                        v-model.number="localItemsPerPage"
                        id="localItemsPerPage"
                        name="localItemsPerPage"
                        :options="[
                            { value: '5', label: '5' },
                            { value: '10', label: '10' },
                            { value: '20', label: '20' },
                            { value: '50', label: '50' },
                        ]"
                    >
                    </CustomSelect>
                </div>
            </div>
        </div>

        <LoadingSpinner v-if="loading" />

        <div v-else class="overflow-x-auto bg-white dark:bg-slate-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-slate-300 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-600">
                    <tr>
                        <th
                            v-for="(column, index) in headers"
                            :key="index"
                            class="px-3 py-3.5 text-left text-base font-semibold text-slate-900 dark:text-white cursor-pointer"
                            @click="sortColumn(column)"
                        >
                            {{ column.label }}
                            <span v-if="sortBy === column.key">
                                <i v-if="sortDirection === 'asc'" class="ml-2 fas fa-arrow-up" />
                                <i v-if="sortDirection === 'desc'" class="ml-2 fas fa-arrow-down" />
                            </span>
                        </th>
                        <th v-if="showActions" class="relative py-3.5 pr-4 pl-3 sm:pr-6">
                            <span class="sr-only">Ações</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:bg-slate-700">
                    <tr
                        v-for="(item, index) in paginatedItems"
                        :key="index"
                        :class="getRowClass(item)"
                        :style="{ cursor: clickableRows ? 'pointer' : 'default' }"
                        @click="handleRowClick(item)"
                    >
                        <td
                            v-for="(column, colIndex) in headers"
                            :key="colIndex"
                            class="px-3 py-5 text-base text-slate-500 dark:text-slate-300"
                        >
                            <div v-if="column.customRender">
                                <slot :name="column.customRender" :item="item" />
                            </div>
                            <div v-else>
                                <div v-if="column.keys && Array.isArray(column.keys)">
                                    <div v-for="(subKey, subIndex) in column.keys" :key="subIndex">
                                        <span
                                            v-if="item[subKey]"
                                            :class="
                                                subIndex === 0
                                                    ? 'font-semibold text-slate-900 dark:text-white'
                                                    : 'text-slate-500 dark:text-slate-400'
                                            "
                                        >
                                            {{ item[subKey] }}
                                        </span>
                                    </div>
                                </div>
                                <div v-else>
                                    {{ item[column.key] }}
                                </div>
                            </div>
                        </td>
                        <td v-if="showActions" class="relative py-5 pr-4 pl-3 text-right text-base sm:pr-6">
                            <button
                                :class="[
                                    'hover:opacity-80 transition',
                                    actionType === 'delete'
                                        ? 'text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300'
                                        : 'text-sky-600 dark:text-sky-400 hover:text-sky-900 dark:hover:text-sky-300',
                                ]"
                                @click="$emit(actionType === 'delete' ? 'delete' : 'edit', item)"
                            >
                                <component
                                    :is="actionType === 'delete' ? 'TrashIcon' : 'PencilSquareIcon'"
                                    class="size-5 shrink-0"
                                    aria-hidden="true"
                                />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="!loading && sortedItems.length > 0" class="flex flex-col sm:flex-row items-center justify-between mt-4 px-2">
            <div class="text-base text-slate-400">Mostrando {{ startRecord }} - {{ endRecord }} de {{ sortedItems.length }}</div>
            <div class="flex items-center space-x-1 mt-2 sm:mt-0">
                <Tooltip text="Primeira página">
                    <button :disabled="currentPage === 1" class="btn" @click="changePage(1)">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                        </svg>
                    </button>
                </Tooltip>
                <Tooltip text="Página anterior">
                    <button :disabled="currentPage === 1" class="btn" @click="prevPage">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                </Tooltip>

                <button v-if="currentPage > 3" class="btn" @click="changePage(1)">1</button>
                <span v-if="currentPage > 4" class="text-base text-slate-400 px-1">...</span>

                <template v-for="page in paginationRange" :key="page">
                    <button
                        :class="['btn', page === currentPage ? 'bg-slate-200 dark:bg-slate-600 text-slate-900 dark:text-white' : '']"
                        @click="changePage(page)"
                    >
                        {{ page }}
                    </button>
                </template>

                <span v-if="currentPage < totalPages - 3" class="text-base text-slate-400 px-1">...</span>
                <button v-if="currentPage < totalPages - 2" class="btn" @click="changePage(totalPages)">
                    {{ totalPages }}
                </button>

                <Tooltip text="Página seguinte">
                    <button :disabled="currentPage === totalPages" class="btn" @click="nextPage">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </Tooltip>

                <Tooltip text="Última página">
                    <button :disabled="currentPage === totalPages" class="btn" @click="changePage(totalPages)">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </Tooltip>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Table',
    props: {
        headers: Array,
        items: Array,
        itemsPerPage: Number,
        showSearch: Boolean,
        showActions: Boolean,
        loading: Boolean,
        clickableRows: Boolean,
        actionType: {
            type: String,
            default: 'edit', // pode ser 'edit' ou 'delete'
        },
    },
    data() {
        return {
            searchQuery: '',
            currentPage: 1,
            localItemsPerPage: this.itemsPerPage || 5,
            sortBy: null,
            sortDirection: 'asc',
        };
    },
    computed: {
        filteredItems() {
            if (!this.showSearch || !this.searchQuery) return this.items;
            const query = this.searchQuery.toLowerCase();
            return this.items.filter(item =>
                this.headers.some(column => {
                    if (column.keys && Array.isArray(column.keys)) {
                        return column.keys.some(key => item[key]?.toString().toLowerCase().includes(query));
                    } else {
                        return item[column.key]?.toString().toLowerCase().includes(query);
                    }
                })
            );
        },
        sortedItems() {
            if (!this.sortBy) return this.filteredItems;

            const parseValue = val => {
                if (val === null || val === undefined) return null;
                if (typeof val !== 'string') return val;

                let str = val.trim();

                str = str.replace(/^R\$\s?/, '').replace(/\s/g, '');

                if (/%$/.test(str)) {
                    const numPercent = str.replace('%', '').replace(/\./g, '').replace(',', '.');
                    if (!isNaN(numPercent)) {
                        return parseFloat(numPercent) / 100;
                    }
                }

                const numNormalized = str.replace(/\./g, '').replace(',', '.');
                if (!isNaN(numNormalized) && numNormalized !== '') {
                    return parseFloat(numNormalized);
                }

                const dateMatch = str.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
                if (dateMatch) {
                    const [, d, m, y] = dateMatch;
                    return new Date(`${y}-${m}-${d}`);
                }

                const dateObj = new Date(str);
                if (!isNaN(dateObj.getTime())) {
                    return dateObj;
                }

                return str.toLowerCase();
            };

            return [...this.filteredItems].sort((a, b) => {
                const valA = parseValue(a[this.sortBy]);
                const valB = parseValue(b[this.sortBy]);

                if (typeof valA === 'number' && typeof valB === 'number') {
                    return this.sortDirection === 'asc' ? valA - valB : valB - valA;
                }

                if (valA instanceof Date && valB instanceof Date) {
                    return this.sortDirection === 'asc' ? valA - valB : valB - valA;
                }

                if (typeof valA === 'string' && typeof valB === 'string') {
                    if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
                    if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                }

                return 0;
            });
        },
        paginatedItems() {
            const start = (this.currentPage - 1) * this.localItemsPerPage;
            return this.sortedItems.slice(start, start + this.localItemsPerPage);
        },
        totalPages() {
            return Math.ceil(this.sortedItems.length / this.localItemsPerPage);
        },
        paginationRange() {
            const range = [];
            const start = Math.max(this.currentPage - 2, 2);
            const end = Math.min(this.currentPage + 2, this.totalPages - 1);
            for (let i = start; i <= end; i++) range.push(i);
            return range;
        },
        startRecord() {
            return (this.currentPage - 1) * this.localItemsPerPage + 1;
        },
        endRecord() {
            return Math.min(this.currentPage * this.localItemsPerPage, this.sortedItems.length);
        },
    },
    watch: {
        searchQuery() {
            this.currentPage = 1;
        },
        localItemsPerPage() {
            this.currentPage = 1;
        },
    },
    methods: {
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) this.currentPage = page;
        },
        sortColumn(column) {
            if (this.sortBy === column.key) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column.key;
                this.sortDirection = 'asc';
            }
        },
        handleRowClick(item) {
            this.$emit('row-clicked', item);
        },
        getRowClass(item) {
            switch (item._RowVariant) {
                case 'green':
                    return 'bg-green-50 hover:bg-green-100 dark:bg-green-800 dark:hover:bg-green-700';
                case 'red':
                    return 'bg-red-50 hover:bg-red-100 dark:bg-red-800 dark:hover:bg-red-700';
                case 'yellow':
                    return 'bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-800 dark:hover:bg-yellow-700';
                default:
                    return 'hover:bg-slate-100 dark:hover:bg-slate-500';
            }
        },
    },
};
</script>

<style scoped>
@reference "tailwindcss";
.btn {
    @apply w-8 h-8 flex items-center justify-center text-base rounded-md border dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 disabled:opacity-50;
}
</style>
