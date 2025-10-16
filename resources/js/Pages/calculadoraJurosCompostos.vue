<template>
    <div class="bg-gray-900">
        <main>
            <div class="relative isolate overflow-hidden bg-gray-900 min-h-screen pt-32 sm:px-64 sm:pt-0">
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 px-4 py-10 sm:px-6 md:grid-cols-6 lg:px-8">
                    <div class="sm:col-span-6 text-center text-2xl">
                        <legend class="text-white">Calculadora juros compostos</legend>
                    </div>
                    <div class="sm:col-span-3">
                        <CustomInput v-model="codGrupo" label="Código grupo" id="codGrupo" name="codGrupo" />
                    </div>
                    <div class="sm:col-span-3">
                        <CustomInput v-model="codGrupo" label="Código grupo" id="codGrupo" name="codGrupo" />
                    </div>
                    <div class="sm:col-span-3">
                        <CustomInput v-model="codGrupo" label="Código grupo" id="codGrupo" name="codGrupo" />
                    </div>
                    <div class="sm:col-span-3">
                        <CustomInput v-model="codGrupo" label="Código grupo" id="codGrupo" name="codGrupo" />
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    data() {
        return {
            openDrop1: false,
            openModalContato: false,
            openModalDescricao: false,

            nome: null,
            email: null,
            assunto: null,
            mensagem: null,
        };
    },
};
</script>
