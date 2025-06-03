<script setup lang="ts">

import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';

function logout() {
    router.post('/logout', {}, {
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

watch(search, (value) => {
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
    const url = editMode.value ? `/industri/${currentIndustri.value.id}` : '/industri';
    const method = editMode.value ? form.put : form.post;

    method(url, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        }
    });
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
}

.industri-pagination :deep(.pagination-nav) {
    justify-content: flex-start;
}

.pkl-pagination, .industri-pagination {
    width: 100%;
    overflow-x: auto;
}
</style>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Profile Card Kiri -->
                <div class="p-6 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="text-xl font-bold mb-4">Profile Siswa</h2>
                    <div class="flex flex-col items-center mb-4">
                        <div class="w-32 h-32 rounded-full overflow-hidden mb-3 border-2 border-sidebar-border/70">
                            <img 
                                :src="siswa.gambar ? `public/images/siswa/${siswa.gambar}` : '/images/default-avatar.png'"
                                :alt="siswa.nama"
                                class="w-full h-full object-cover"
                            >
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p><strong>Nama:</strong> {{ siswa.nama }}</p>
                        <p><strong>NIS:</strong> {{ siswa.nis }}</p>
                        <p><strong>Email:</strong> {{ siswa.email }}</p>
                        <p><strong>Gender:</strong> {{ siswa.gender }}</p>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="p-6 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
                    <div class="space-y-2">
                        <button @click="openModal()" class="w-full border border-gray-500 px-4 py-2 rounded hover:bg-gray-500 hover:text-white transition-colors">
                            Tambah Data Industri
                        </button>
                        <button 
                            v-if="!siswa.status_pkl"
                            @click="showPklModal = true" 
                            class="w-full border border-gray-500 px-4 py-2 rounded hover:bg-gray-500 hover:text-white transition-colors">
                            Tambah Data PKL
                        </button>
                    </div>
                </div>

                <!-- Profile Card Kanan -->
                <div class="p-6 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h2 class="text-xl font-bold mb-4">Info Tambahan</h2>
                    <div class="space-y-2">
                        <p><strong>Alamat:</strong> {{ siswa.alamat }}</p>
                        <p><strong>Kontak:</strong> {{ siswa.kontak }}</p>
                        <p>
                            <strong>Status PKL:</strong>
                            <span :class="siswa.status_pkl ? 'text-green-500' : 'text-red-500'">
                                {{ siswa.status_pkl ? 'Sudah Mendapat PKL' : 'Belum Mendapat PKL' }}
                            </span>
                        </p>
                        <div class="pt-4 border-t border-sidebar-border/70 mt-4">
                            <button 
                                @click="logout" 
                                class="w-full border border-red-500 text-red-500 px-4 py-2 rounded hover:bg-red-500 hover:text-white transition-colors">
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tables Section -->
            <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
                <!-- PKL Table -->
                <div class="p-6 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Data PKL</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-sidebar-border/70">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Industri</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Guru Pembimbing</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Mulai</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Selesai</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="p in pkl.data" :key="p.id" 
                                    :class="{
                                        'border-b border-sidebar-border/70': true,
                                        'bg-gray-900/5': p.siswa_id === siswa.id
                                    }">
                                    <td class="px-6 py-4">{{ p.siswa?.nama }}</td>
                                    <td class="px-6 py-4">{{ p.industri?.nama }}</td>
                                    <td class="px-6 py-4">{{ p.guru?.name }}</td>
                                    <td class="px-6 py-4">{{ new Date(p.mulai).toLocaleDateString() }}</td>
                                    <td class="px-6 py-4">{{ new Date(p.selesai).toLocaleDateString() }}</td>
                                    <td class="px-6 py-4">
                                        <div v-if="p.siswa_id === siswa.id" class="flex space-x-2">
                                            <button 
                                                @click="openEditPklModal(p)"
                                                class="border border-gray-500 px-2 py-1 rounded hover:bg-gray-500 hover:text-white transition-colors">
                                                Edit
                                            </button>
                                            <button 
                                                @click="deletePkl(p.id)" 
                                                class="border border-red-500 text-red-500 px-2 py-1 rounded hover:bg-red-500 hover:text-white transition-colors">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination for PKL table -->
                    <div v-if="pkl.links" class="mt-4 pkl-pagination">
                        <Pagination 
                            :links="pkl.links" 
                            :preserve-state="true"
                            :preserve-scroll="true"
                            :only="['pkl']"
                        />
                    </div>
                </div>

                <!-- Industri Table -->
                <div class="p-6 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Data Industri</h2>
                        <div class="flex space-x-2 items-center">
                            <input 
                                type="text" 
                                v-model="search" 
                                placeholder="Cari industri..." 
                                class="px-4 py-2 border rounded bg-transparent"
                            >
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-sidebar-border/70">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Nama</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Bidang Usaha</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Alamat</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Kontak</th>
                                    <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="industri in industris.data" :key="industri.id" class="border-b border-sidebar-border/70">
                                    <td class="px-6 py-4">{{ industri.nama }}</td>
                                    <td class="px-6 py-4">{{ industri.bidang_usaha }}</td>
                                    <td class="px-6 py-4">{{ industri.alamat }}</td>
                                    <td class="px-6 py-4">{{ industri.kontak }}</td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <button @click="openModal(industri)" class="border border-gray-500 px-2 py-1 rounded hover:bg-gray-500 hover:text-white transition-colors">
                                            Edit
                                        </button>
                                        <button @click="deleteIndustri(industri.id)" class="border border-red-500 text-red-500 px-2 py-1 rounded hover:bg-red-500 hover:text-white transition-colors">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="industris.links" class="mt-4 industri-pagination">
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
