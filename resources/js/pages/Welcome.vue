<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
</script>

<template>
    <Head title="Selamat Datang" />
    
    <div class="min-h-screen flex items-center justify-center bg-gray-50 p-4">
        <div class="w-full max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 mb-2">Portal PKL</h1>
                <p class="text-lg text-gray-600">Silahkan pilih peran Anda untuk melanjutkan</p>
            </div>

            <!-- Cards Container -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Guru Card -->
                <Card class="relative overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-black rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">Guru</h2>
                        <p class="text-gray-600 mb-6">Masuk sebagai guru pembimbing untuk mengelola dan memantau siswa PKL</p>
                        <div class="mt-4">
                            <Link :href="route('guru.login')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Masuk sebagai Guru
                            </Link>
                        </div>
                    </div>
                </Card>

                <!-- Siswa Card -->
                <Card class="relative overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-black rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">Siswa</h2>
                        <p class="text-gray-600 mb-6">Daftar atau masuk sebagai siswa untuk mengakses informasi dan mendaftar PKL</p>
                        <div class="space-y-3">
                            <Link :href="route('siswa.login')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                                Masuk sebagai Siswa
                            </Link>
                            <Link :href="route('siswa.register')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Daftar Akun Baru
                            </Link>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-sm text-gray-500">
                © {{ new Date().getFullYear() }} Portal PKL. All rights reserved.
            </div>
        </div>
    </div>
</template>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
</style>
