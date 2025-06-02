<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    nama: '',
    nis: '',
    email: '',
    password: '',
    password_confirmation: '',
    gender: '',
    alamat: '',
    kontak: '',
    gambar: null
});

const submit = () => {
    form.post(route('siswa.register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Registrasi Siswa" description="Lengkapi data diri anda untuk membuat akun">
        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="nama">Nama Lengkap</Label>
                    <Input id="nama" type="text" required autofocus :tabindex="1" v-model="form.nama" placeholder="Nama lengkap" />
                    <InputError :message="form.errors.nama" />
                </div>

                <div class="grid gap-2">
                    <Label for="nis">NIS</Label>
                    <Input id="nis" type="text" required :tabindex="2" v-model="form.nis" placeholder="Nomor Induk Siswa" />
                    <InputError :message="form.errors.nis" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" required :tabindex="3" v-model="form.email" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="gender">Jenis Kelamin</Label>
                    <select id="gender" v-model="form.gender" required :tabindex="4" class="rounded-md border">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <InputError :message="form.errors.gender" />
                </div>

                <div class="grid gap-2">
                    <Label for="alamat">Alamat</Label>
                    <textarea id="alamat" v-model="form.alamat" required :tabindex="5" class="rounded-md border" rows="3"></textarea>
                    <InputError :message="form.errors.alamat" />
                </div>

                <div class="grid gap-2">
                    <Label for="kontak">No. Telepon</Label>
                    <Input id="kontak" type="text" required :tabindex="6" v-model="form.kontak" placeholder="Nomor telepon" />
                    <InputError :message="form.errors.kontak" />
                </div>

                <div class="grid gap-2">
                    <Label for="gambar">Foto</Label>
                    <Input id="gambar" type="file" accept="image/*" :tabindex="7" @input="form.gambar = $event.target.files[0]" />
                    <InputError :message="form.errors.gambar" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" required :tabindex="8" v-model="form.password" placeholder="Password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input id="password_confirmation" type="password" required :tabindex="9" v-model="form.password_confirmation" placeholder="Konfirmasi password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" :tabindex="10" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Daftar
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Sudah punya akun?
                <TextLink :href="route('siswa.login')" class="underline underline-offset-4" :tabindex="11">Masuk</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
