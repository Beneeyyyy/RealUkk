<script setup lang="ts">

import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import { Link } from '@inertiajs/vue3';


function logout() {
    router.post(route('siswa.logout'), {}, {
        onSuccess: () => {
            router.visit('/');
        }
    });
}




const props = defineProps(['siswa', 'industris', 'pkl', 'currentStudentPkl', 'gurus', 'allIndustris']);
const search = ref('');
const showModal = ref(false);
const showPklModal = ref(false);
const showEditPklModal = ref(false);
const currentPkl = ref(null);
const editMode = ref(false);
const currentIndustri = ref(null);
const searchIndustri = ref('');
const searchPkl = ref('');



watch(searchIndustri, (value) => {
    router.get(
        '/siswa/dashboard',
        { search: value, remember: true },
        { 
            preserveState: true,
            preserveScroll: true,
            only: ['industris']
        }
    );
});

watch(searchPkl, (value) => {
    router.get(
        '/siswa/dashboard',
        { search: value, remember: true },
        { 
            preserveState: true,
            preserveScroll: true,
            only: ['pkl']
        }
    );
});

const form = useForm({
    nama: '',
    bidang_usaha: '',
    alamat: '',
    kontak: '',
    email: ''
});

const pklForm = useForm({
    industri_id: '',
    guru_id: '',
    mulai: '',
    selesai: ''
});

const editPklForm = useForm({
    industri_id: '',
    guru_id: '',
    mulai: '',
    selesai: ''
});

function openEditPklModal(pkl) {
    currentPkl.value = pkl;
    editPklForm.industri_id = pkl.industri_id;
    editPklForm.guru_id = pkl.guru_id;
    editPklForm.mulai = pkl.mulai;
    editPklForm.selesai = pkl.selesai;
    showEditPklModal.value = true;
}

function submitEditPkl() {
    editPklForm.put(`/pkl/${currentPkl.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditPklModal.value = false;
            editPklForm.reset();
        }
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

function openModal(industri = null) {
    if (industri) {
        editMode.value = true;
        currentIndustri.value = industri;
        form.nama = industri.nama;
        form.bidang_usaha = industri.bidang_usaha;
        form.alamat = industri.alamat;
        form.kontak = industri.kontak;
        form.email = industri.email;
    } else {
        editMode.value = false;
        form.reset();
    }
    showModal.value = true;
}

function submitIndustri() {
    if (editMode.value) {
        form.put(`/industri/${currentIndustri.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                form.reset();
                editMode.value = false;
            }
        });
    } else {
        form.post('/industri', {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            }
        });
    }
}

function deleteIndustri(id) {
    if (confirm('Are you sure you want to delete this industry?')) {
        router.delete(`/industri/${id}`, {
            preserveScroll: true
        });
    }
}

function submitPkl() {
    pklForm.post('/pkl', {
        preserveScroll: true,
        onSuccess: () => {
            showPklModal.value = false;
            pklForm.reset();
        }
    });
}

function deletePkl(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data PKL ini?')) {
        router.delete(`/pkl/${id}`, {
            preserveScroll: true
        });
    }
}


</script>

<style scoped>
.pkl-pagination :deep(.pagination-nav),
.industri-pagination :deep(.pagination-nav) {
    display: flex;
    justify-content: flex-end;
}

.pkl-pagination :deep(.pagination-nav .page-link),
.industri-pagination :deep(.pagination-nav .page-link) {
    margin-left: 0.5rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
}

.pkl-pagination :deep(.pagination-nav .page-link:hover) {
    background-color: #f9fafb;
}

.pkl-pagination :deep(.pagination-nav .page-link.active),
.industri-pagination :deep(.pagination-nav .page-link.active) {
    background-color: #f3f4f6;
    font-weight: 500;
}
</style>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen max-h-screen flex flex-col gap-4 p-4 bg:black">
            <!-- Top Section: Profile & Actions -->
            <div class="grid md:grid-cols-3 gap-4">
                <!-- Profile Card -->
                <div class="p-4 rounded-xl border border-gray-200 bg-black shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-100 flex-shrink-0">
                            <img 
                                :src="siswa.gambar ? `/storage/${siswa.gambar}` : '/images/default-avatar.png'"
                                :alt="siswa.nama"
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-bold truncate">{{ siswa.nama }}</h2>
                            <div class="mt-1 space-y-1 text-sm white">
                                <p class="flex items-center gap-2">
                                    <span class="font-medium">NIS:</span> 
                                    <span class="text-white">{{ siswa.nis }}</span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <span class="font-medium">Email:</span>
                                    <span class="text-white truncate">{{ siswa.email }}</span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <span class="font-medium">Status PKL:</span>
                                    <span :class="siswa.status_pkl ? 'bg-black text-white' : 'bg-gray-100 text-gray-700'"
                                          class="px-2 py-0.5 rounded-full text-xs">
                                        {{ siswa.status_pkl ? 'Aktif PKL' : 'Belum PKL' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="p-4 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-bold mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <button @click="openModal()" 
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg hover: hover:border-gray-400 transition-all text-sm font-medium flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Data Industri
                        </button>
                        <button v-if="!siswa.status_pkl"
                            @click="showPklModal = true" 
                            class="w-full bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-all text-sm font-medium flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd" />
                            </svg>
                            Daftar PKL Baru
                        </button>
                    </div>

                    <!-- Current PKL Info -->
                    <template v-if="siswa.status_pkl && currentStudentPkl?.length">
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h3 class="font-medium text-gray-900 mb-3">Status PKL Saat Ini</h3>
                            <div v-for="p in currentStudentPkl" :key="p.id" 
                                class="rounded-lg p-3">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium">Industri:</span>
                                        <span class="text-right">{{ p.industri?.nama || '-' }}</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium">Pembimbing:</span>
                                        <span class="text-right">{{ p.guru?.name || '-' }}</span>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium">Periode:</span>
                                        <span class="text-right">
                                            {{ p.mulai ? new Date(p.mulai).toLocaleDateString('id-ID', { dateStyle: 'medium' }) : '-' }}
                                            s/d
                                            {{ p.selesai ? new Date(p.selesai).toLocaleDateString('id-ID', { dateStyle: 'medium' }) : '-' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-3 pt-2 border-t border-gray-200">
                                    <button @click="openEditPklModal(p)"
                                        class="flex-1  text-white border border-gray-300 px-3 py-1.5 rounded hover:bg-gray-50 transition-colors text-black font-medium">
                                        Edit Data
                                    </button>
                                    <button @click="deletePkl(p.id)"
                                        class="flex-1 border border-red-500 text-red-500 px-3 py-1.5 rounded hover:bg-red-10 transition-colors text-xs font-medium">
                                        Hapus Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Additional Info -->
                <div class="p-4 rounded-xl border border-gray-200  shadow-sm">
                    <h3 class="text-lg font-bold mb-3">Info Tambahan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <div class="flex-1">
                                <p class="font-medium text-white">Alamat</p>
                                <p class="text-white mt-0.5">{{ siswa.alamat || '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                            <div class="flex-1">
                                <p class="font-medium text-white">Kontak</p>
                                <p class="text-white mt-0.5">{{ siswa.kontak || '-' }}</p>
                            </div>
                        </div>
                        <button @click="logout" 
                            class="w-full mt-4 border border-red-500 text-red-500 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors text-sm font-medium">
                            Logout
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Tables -->
            <div class="grid md:grid-cols-2 gap-4 flex-1 min-h-0">
                <!-- PKL Table -->
                <div class="p-4 rounded-xl border border-gray-200  shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold">Data PKL Siswa</h2>
                        <div class="relative">
                            <input type="text" 
                                v-model="searchPkl" 
                                placeholder="Cari data PKL..." 
                                class="pl-8 pr-4 py-1.5 border border-gray-200 rounded-lg text-sm w-64 focus:outline-none focus:border-gray-400 transition-colors"
                            />
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-2.5 top-2.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="flex-1 overflow-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Siswa</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Industri</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Pembimbing</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Periode</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 ">
                                <template v-if="pkl?.data?.length">
                                    <tr v-for="p in pkl.data" :key="p.id" class="text-white hover:bg-gray-50 hover:text-black">
                                        <td class="px-4 py-2 text-sm">{{ p.siswa?.nama || '-' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ p.industri?.nama || '-' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ p.guru?.name || '-' }}</td>
                                        <td class="px-4 py-2 text-sm whitespace-nowrap">
                                            {{ new Date(p.mulai).toLocaleDateString('id-ID', { dateStyle: 'medium' }) }}
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="4" class="px-4 py-2 text-sm text-center text-black">
                                        Belum ada data PKL
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div v-if="pkl.links" class="mt-4 border-t border-gray-200 pt-4 pkl-pagination">
                        <Pagination :links="pkl.links" :preserve-state="true" :preserve-scroll="true" :only="['pkl']" />
                    </div>
                </div>

                <!-- Industri Table -->
                <div class="p-4 rounded-xl border border-gray-200  shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold">Data Industri</h2>
                        <div class="relative">
                            <input type="text" 
                                v-model="searchIndustri" 
                                placeholder="Cari industri..." 
                                class="pl-8 pr-4 py-1.5 border border-gray-200 rounded-lg text-sm w-64 focus:outline-none focus:border-gray-400 transition-colors"
                            />
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-2.5 top-2.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="flex-1 overflow-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class=" sticky top-0 bg-white">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Nama</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Bidang</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Kontak</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 ">
                                <template v-if="industris.data?.length">
                                    <tr v-for="industri in industris.data" :key="industri.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm font-medium text-white">{{ industri.nama }}</td>
                                        <td class="px-4 py-2 text-sm text-white">{{ industri.bidang_usaha }}</td>
                                        <td class="px-4 py-2 text-sm text-white">{{ industri.kontak }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="flex gap-2">
                                                <button @click="openModal(industri)" 
                                                    class="text-xs px-2 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                                                    Edit
                                                </button>
                                                <button @click="deleteIndustri(industri.id)" 
                                                    class="text-xs px-2 py-1 rounded-md bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="4" class="px-4 py-2 text-sm text-center text-white">
                                        Belum ada data industri
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div v-if="industris.links" class="mt-4 border-t border-gray-200 pt-4 industri-pagination">
                        <Pagination :links="industris.links" :preserve-state="true" :preserve-scroll="true" :only="['industris']" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Industri Form -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-background p-6 rounded-lg w-full max-w-md border border-sidebar-border/70">
                <h3 class="text-lg font-bold mb-4">{{ editMode ? 'Edit Industri' : 'Tambah Industri Baru' }}</h3>
                <form @submit.prevent="submitIndustri">
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1">Nama</label>
                            <input v-model="form.nama" type="text" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                        </div>
                        <div>
                            <label class="block mb-1">Bidang Usaha</label>
                            <input v-model="form.bidang_usaha" type="text" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                        </div>
                        <div>
                            <label class="block mb-1">Alamat</label>
                            <textarea v-model="form.alamat" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent"></textarea>
                        </div>
                        <div>
                            <label class="block mb-1">Kontak</label>
                            <input v-model="form.kontak" type="text" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                        </div>
                        <div>
                            <label class="block mb-1">Email</label>
                            <input v-model="form.email" type="email" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 border border-sidebar-border/70 rounded hover:bg-gray-500 hover:text-white transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 border border-gray-500 rounded hover:bg-gray-500 hover:text-white transition-colors">
                            {{ editMode ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal for PKL Form -->
        <div v-if="showPklModal && !siswa.status_pkl" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-background p-6 rounded-lg w-full max-w-md border border-sidebar-border/70">
                <h3 class="text-lg font-bold mb-4">Tambah Data PKL</h3>
                <form @submit.prevent="submitPkl">
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1">Pilih Industri</label>
                            <select v-model="pklForm.industri_id" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-black ">
                                <option value="">Pilih Industri</option>
                                <option v-for="industri in props.allIndustris" :key="industri.id" :value="industri.id">
                                    {{ industri.nama }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Pilih Guru Pembimbing</label>
                            <select v-model="pklForm.guru_id" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-black">
                                <option value="">Pilih Guru Pembimbing</option>
                                <option v-for="guru in gurus" :key="guru.id" :value="guru.id">
                                    {{ guru.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Tanggal Mulai</label>
                            <input v-model="pklForm.mulai" type="date" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-black">
                        </div>
                        <div>
                            <label class="block mb-1">Tanggal Selesai</label>
                            <input v-model="pklForm.selesai" type="date" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-black">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" @click="showPklModal = false" class="px-4 py-2 border border-sidebar-border/70 rounded hover:bg-gray-500 hover:text-white transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 border border-gray-500 rounded hover:bg-gray-500 hover:text-white transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal for Edit PKL Form -->
        <div v-if="showEditPklModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-background p-6 rounded-lg w-full max-w-md border border-sidebar-border/70">
                <h3 class="text-lg font-bold mb-4">Edit Data PKL</h3>
                <form @submit.prevent="submitEditPkl">
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1">Pilih Industri</label>
                            <select v-model="editPklForm.industri_id" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-black">
                                <option value="">Pilih Industri</option>
                                <option v-for="industri in props.allIndustris" :key="industri.id" :value="industri.id">
                                    {{ industri.nama }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Pilih Guru Pembimbing</label>
                            <select v-model="editPklForm.guru_id" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-black">
                                <option value="">Pilih Guru Pembimbing</option>
                                <option v-for="guru in gurus" :key="guru.id" :value="guru.id">
                                    {{ guru.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Tanggal Mulai</label>
                            <input v-model="editPklForm.mulai" type="date" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-black">
                        </div>
                        <div>
                            <label class="block mb-1">Tanggal Selesai</label>
                            <input v-model="editPklForm.selesai" type="date" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-tblack">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" @click="showEditPklModal = false" class="px-4 py-2 border border-sidebar-border/70 rounded hover:bg-gray-500 hover:text-white transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 border border-gray-500 rounded hover:bg-gray-500 hover:text-white transition-colors">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
