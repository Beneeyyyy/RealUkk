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




const props = defineProps(['siswa', 'industris', 'pkl', 'gurus', 'allIndustris']);
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
.pkl-pagination :deep(.pagination-nav) {
    justify-content: flex-start;
    margin-top: 0.5rem;
}

.pkl-pagination :deep(.pagination-nav .page-link) {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.industri-pagination :deep(.pagination-nav) {
    justify-content: flex-start;
    margin-top: 0.5rem;
}

.industri-pagination :deep(.pagination-nav .page-link) {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.pkl-pagination, .industri-pagination {
    width: 100%;
    overflow-x: auto;
}
</style>

<template>
    <Head title="Dashboard" />


    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-screen flex-1 flex-col gap-2 rounded-xl p-2">
            <!-- Profile and Quick Actions Section -->
            <div class="grid auto-rows-min gap-2 md:grid-cols-3 h-1/3">
                <!-- Profile Card Kiri -->
                <div class="p-3 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="text-lg font-bold mb-2">Profile Siswa</h2>
                    <div class="flex gap-3">
                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-sidebar-border/70">
                            <img 
                                :src="siswa.gambar ? `/storage/${siswa.gambar}` : '/images/default-avatar.png'"
                                :alt="siswa.nama"
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <div class="space-y-1 text-sm">
                            <p><strong>Nama:</strong> {{ siswa.nama }}</p>
                            <p><strong>NIS:</strong> {{ siswa.nis }}</p>
                            <p><strong>Email:</strong> {{ siswa.email }}</p>
                            <p><strong>Gender:</strong> {{ siswa.gender }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="p-3 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="text-lg font-bold mb-2">Quick Actions</h2>
                    <div class="space-y-2">
                        <button @click="openModal()" class="w-full border border-gray-500 px-3 py-1.5 rounded hover:bg-gray-500 hover:text-white transition-colors text-sm">
                            Tambah Data Industri
                        </button>
                        <button 
                            v-if="!siswa.status_pkl"
                            @click="showPklModal = true" 
                            class="w-full border border-gray-500 px-3 py-1.5 rounded hover:bg-gray-500 hover:text-white transition-colors text-sm">
                            Tambah Data PKL
                        </button>
                    </div>
                </div>

                <!-- Profile Card Kanan -->
                <div class="p-3 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="text-lg font-bold mb-2">Info Tambahan</h2>
                    <div class="space-y-1 text-sm">
                        <p><strong>Alamat:</strong> {{ siswa.alamat }}</p>
                        <p><strong>Kontak:</strong> {{ siswa.kontak }}</p>
                        <p>
                            <strong>Status PKL:</strong>
                            <span :class="siswa.status_pkl ? 'text-green-500' : 'text-red-500'">
                                {{ siswa.status_pkl ? 'Sudah Mendapat PKL' : 'Belum Mendapat PKL' }}
                            </span>
                        </p>
                        <div class="pt-2 border-t border-sidebar-border/70 mt-2">
                            <button 
                               @click="logout" 
                                class="w-full border border-red-500 text-red-500 px-3 py-1.5 rounded hover:bg-red-500 hover:text-white transition-colors text-sm">
                                Logout
                            </button n>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tables Section -->
            <div class="grid md:grid-cols-2 grid-cols-1 gap-2 h-2/3">
                <!-- PKL Table -->
                <div class="p-3 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col h-full overflow-hidden">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-bold">Data PKL</h2>
                        <div class="flex space-x-2 items-center">
                           <input 
                              type="text" 
                              v-model="searchPkl" 
                              placeholder="Cari PKL..." 
                              class="px-3 py-1 border rounded bg-transparent text-sm"
                            />
                        </div>
                    </div>
                    <div class="overflow-auto flex-1">
                        <table class="min-w-full border-collapse border border-sidebar-border/70">
                            <thead class="sticky top-0 bg-background">
                                <tr>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Nama Siswa</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Industri</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Guru Pembimbing</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Mulai</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Selesai</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-for="p in pkl.data" :key="p.id" 
                                    :class="{
                                        'border-b border-sidebar-border/70': true,
                                        'bg-gray-900/5': p.siswa_id === siswa.id
                                    }">
                                    <td class="px-4 py-2">{{ p.siswa?.nama }}</td>
                                    <td class="px-4 py-2">{{ p.industri?.nama }}</td>
                                    <td class="px-4 py-2">{{ p.guru?.name }}</td>
                                    <td class="px-4 py-2">{{ new Date(p.mulai).toLocaleDateString() }}</td>
                                    <td class="px-4 py-2">{{ new Date(p.selesai).toLocaleDateString() }}</td>
                                    <td class="px-4 py-2">
                                        <div v-if="p.siswa_id === siswa.id" class="flex space-x-2">
                                            <button 
                                                @click="openEditPklModal(p)"
                                                class="border border-gray-500 px-2 py-0.5 rounded hover:bg-gray-500 hover:text-white transition-colors text-xs">
                                                Edit
                                            </button>
                                            <button 
                                                @click="deletePkl(p.id)" 
                                                class="border border-red-500 text-red-500 px-2 py-0.5 rounded hover:bg-red-500 hover:text-white transition-colors text-xs">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="pkl.links" class="mt-2 pkl-pagination">
                        <Pagination 
                            :links="pkl.links"
                            :preserve-state="true"
                            :preserve-scroll="true"
                            :only="['pkl']"
                        />
                    </div>
                </div>

                <!-- Industri Table -->
                <div class="p-3 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col h-full overflow-hidden">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-bold">Data Industri</h2>
                        <div class="flex space-x-2 items-center">
                           <input 
                              type="text" 
                              v-model="searchIndustri" 
                              placeholder="Cari industri..." 
                              class="px-3 py-1 border rounded bg-transparent text-sm"
                            />
                        </div>
                    </div>
                    <div class="overflow-auto flex-1">
                        <table class="min-w-full border-collapse border border-sidebar-border/70">
                            <thead class="sticky top-0 bg-background">
                                <tr>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Nama</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Bidang Usaha</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Alamat</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Kontak</th>
                                    <th class="px-4 py-2 text-left border-b border-sidebar-border/70 text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr v-for="industri in industris.data" :key="industri.id" class="border-b border-sidebar-border/70">
                                    <td class="px-4 py-2">{{ industri.nama }}</td>
                                    <td class="px-4 py-2">{{ industri.bidang_usaha }}</td>
                                    <td class="px-4 py-2">{{ industri.alamat }}</td>
                                    <td class="px-4 py-2">{{ industri.kontak }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex space-x-2">
                                            <button @click="openModal(industri)" 
                                                class="border border-gray-500 px-2 py-0.5 rounded hover:bg-gray-500 hover:text-white transition-colors text-xs">
                                                Edit
                                            </button>
                                            <button @click="deleteIndustri(industri.id)" 
                                                class="border border-red-500 text-red-500 px-2 py-0.5 rounded hover:bg-red-500 hover:text-white transition-colors text-xs">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="industris.links" class="mt-2 industri-pagination">
                        <Pagination 
                            :links="industris.links"
                            :preserve-state="true"
                            :preserve-scroll="true"
                            :only="['industris']"
                        />
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
                            <select v-model="pklForm.industri_id" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                                <option value="">Pilih Industri</option>
                                <option v-for="industri in props.allIndustris" :key="industri.id" :value="industri.id">
                                    {{ industri.nama }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Pilih Guru Pembimbing</label>
                            <select v-model="pklForm.guru_id" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                                <option value="">Pilih Guru Pembimbing</option>
                                <option v-for="guru in gurus" :key="guru.id" :value="guru.id">
                                    {{ guru.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Tanggal Mulai</label>
                            <input v-model="pklForm.mulai" type="date" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                        </div>
                        <div>
                            <label class="block mb-1">Tanggal Selesai</label>
                            <input v-model="pklForm.selesai" type="date" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
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
                            <select v-model="editPklForm.industri_id" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                                <option value="">Pilih Industri</option>
                                <option v-for="industri in props.allIndustris" :key="industri.id" :value="industri.id">
                                    {{ industri.nama }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Pilih Guru Pembimbing</label>
                            <select v-model="editPklForm.guru_id" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                                <option value="">Pilih Guru Pembimbing</option>
                                <option v-for="guru in gurus" :key="guru.id" :value="guru.id">
                                    {{ guru.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Tanggal Mulai</label>
                            <input v-model="editPklForm.mulai" type="date" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
                        </div>
                        <div>
                            <label class="block mb-1">Tanggal Selesai</label>
                            <input v-model="editPklForm.selesai" type="date" class="w-full px-4 py-2 border border-sidebar-border/70 rounded bg-transparent">
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
