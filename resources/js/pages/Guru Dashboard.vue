<script setup lang="ts">
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

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

const props = defineProps(['siswa', 'industris', 'pkl', 'gurus']);
const searchPkl = ref('');


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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1>HALO GURU</h1>
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="pt-4 border-t border-sidebar-border/70 mt-4">
                            <button 
                               @click="logout" 
                                class="w-full border border-red-500 text-red-500 px-4 py-2 rounded hover:bg-red-500 hover:text-white transition-colors">
                                Logout
                            </button n>
                        </div>
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                 <div
                        class="p-6 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Data PKL</h2>
                            <div class="flex space-x-2 items-center">
                                <input type="text" v-model="searchPkl" placeholder="Cari PKL..."
                                    class="px-4 py-2 border rounded bg-transparent" />

                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse border border-sidebar-border/70">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Nama Siswa
                                        </th>
                                        <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Industri</th>
                                        <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Guru
                                            Pembimbing</th>
                                        <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Mulai</th>
                                        <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Selesai</th>
                                        <th class="px-6 py-3 text-left border-b border-sidebar-border/70">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="p in pkl.data" :key="p.id" :class="{
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
                                                <button @click="openEditPklModal(p)"
                                                    class="border border-gray-500 px-2 py-1 rounded hover:bg-gray-500 hover:text-white transition-colors">
                                                    Edit
                                                </button>
                                                <button @click="deletePkl(p.id)"
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
                        <div v-if="pkl.links" class="mt-4 industri-pagination">
                            <Pagination :links="pkl.links" :preserve-state="true" :preserve-scroll="true"
                                :only="['pkl']" />
                        </div>
                    </div>
            </div>
        </div>
    </AppLayout>
</template>
