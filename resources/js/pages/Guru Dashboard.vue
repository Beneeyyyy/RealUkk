<script setup lang="ts">
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

interface Guru {
    id: number;
    name: string;
    email: string;
}

interface Siswa {
    id: number;
    nama: string;
}

interface Industri {
    id: number;
    nama: string;
}

interface PKL {
    id: number;
    siswa_id: number;
    guru_id: number;
    industri_id: number;
    mulai: string;
    selesai: string;
    siswa?: Siswa;
    industri?: Industri;
}

interface Props {
    siswa: Siswa[];
    industris: Industri[];
    pkl: {
        data: PKL[];
        links: any;
    };
    gurus: Guru;
}

const props = defineProps<Props>();
const searchPkl = ref('');

function logout() {
    router.post(route('guru.logout'), {}, {
        onSuccess: () => {
            router.visit('/');
        }
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Compute statistics for summary cards
const totalStudentsSupervised = computed(() => 
    props.pkl.data?.filter(p => p.guru_id === props.gurus.id).length || 0
);

const uniqueIndustries = computed(() => {
    const industries = new Set(
        props.pkl.data
            ?.filter(p => p.guru_id === props.gurus.id)
            .map(p => p.industri?.nama)
    );
    return industries.size;
});

const activeStudents = computed(() => 
    props.pkl.data?.filter(p => {
        const endDate = new Date(p.selesai);
        const today = new Date();
        return p.guru_id === props.gurus.id && endDate >= today;
    }).length || 0
);

watch(searchPkl, (value) => {
    router.get(
        '/guru/dashboard',
        { search: value, remember: true },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['pkl']
        }
    );
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-screen flex-col gap-2 rounded-xl p-2">
            <!-- Profile and Summary Section -->
            <div class="grid gap-2 md:grid-cols-4">
                <!-- Profile Card -->
                <div class="p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-white mb-2 bg-white">
                            <img 
                                src="/reading.png" 
                                alt="Profile Icon"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <h2 class="text-base font-bold mb-1">{{ props.gurus.name }}</h2>
                        <p class="text-xs mb-2">{{ props.gurus.email }}</p>
                        <button 
                            @click="logout" 
                            class="w-full border border-red-500 text-red-500 px-3 py-1 rounded hover:bg-red-500 hover:text-white transition-colors text-xs">
                            Logout
                        </button>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col justify-center">
                    <h3 class="text-xs font-semibold mb-1">Total Siswa Bimbingan</h3>
                    <p class="text-2xl font-bold">{{ totalStudentsSupervised }}</p>
                    <p class="text-xs mt-1">Jumlah siswa yang Anda bimbing</p>
                </div>

                <div class="p-4 rounded-xl border border-sidebar border:white dark:border-sidebar-border flex flex-col justify-center">
                    <h3 class="text-xs font-semibold mb-1">Industri Terkait</h3>
                    <p class="text-2xl font-bold">{{ uniqueIndustries }}</p>
                    <p class="text-xs mt-1">Jumlah industri yang terhubung</p>
                </div>

                <div class="p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col justify-center">
                    <h3 class="text-xs font-semibold mb-1">Siswa Aktif PKL</h3>
                    <p class="text-2xl font-bold">{{ activeStudents }}</p>
                    <p class="text-xs mt-1">Siswa yang sedang menjalani PKL</p>
                </div>
            </div>

            <!-- PKL Data Section -->
            <div class="flex-1 rounded-xl border border-sidebar border-white dark:border-sidebar-border overflow-hidden">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold">Data Siswa PKL</h2>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    v-model="searchPkl" 
                                    placeholder="Cari siswa atau industri..." 
                                    class="pl-8 pr-4 py-1.5 border rounded-lg bg-transparent w-56 text-sm"
                                />
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-2.5 top-2.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead>
                                <tr class="border-b border-sidebar-border/70">
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Industri</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Mulai</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Selesai</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-sidebar-border/70">
                                <tr v-for="p in props.pkl.data" :key="p.id" 
                                    class="hover:bg-gray-900/5 transition-colors">
                                    <td class="px-6 py-4 text-sm">{{ p.siswa?.nama }}</td>
                                    <td class="px-6 py-4 text-sm">{{ p.industri?.nama }}</td>
                                    <td class="px-6 py-4 text-sm">{{ new Date(p.mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</td>
                                    <td class="px-6 py-4 text-sm">{{ new Date(p.selesai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span :class="new Date(p.selesai) >= new Date() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                              class="px-2 py-1 rounded-full text-xs font-medium">
                                            {{ new Date(p.selesai) >= new Date() ? 'Aktif' : 'Selesai' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="props.pkl.links" class="mt-4">
                        <Pagination 
                            :links="props.pkl.links" 
                            :preserve-state="true" 
                            :preserve-scroll="true"
                            :only="['pkl']" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.pkl-pagination :deep(.pagination-nav) {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.pkl-pagination :deep(.pagination-nav .page-link) {
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    border: 1px solid rgba(var(--sidebar-border), 0.7);
    font-size: 0.875rem;
}

.pkl-pagination :deep(.pagination-nav .page-link.active) {
    background-color: rgba(0, 0, 0, 0.05);
}
</style>
