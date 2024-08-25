<template>
    <GuestLayout>
        <Head title="Submit Order" />

        <div class="card-header font-extrabold mb-3 border-b-2 border-gray-900 text-2xl">Submit An Order</div>

        <div class="card-body">
            <form @submit.prevent="submit" class="mx-auto">
                <div class="mb-5">
                    <InputLabel for="hmo_code" value="HMO Code" />

                    <TextInput
                        id="hmo_code"
                        class="mt-1 block w-full"
                        v-model="form.hmo_code"
                        placeholder="e.g HMO-823"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.hmo_code" />
                </div>
                <div class="mb-5">
                    <InputLabel for="provider_name" value="Your Name (Provider)" />

                    <TextInput
                        id="provider_name"
                        class="mt-1 block w-full"
                        v-model="form.provider_name"
                        placeholder="e.g Everlasting Health Centre"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.provider_name" />
                </div>
                <div class="mb-5">
                    <InputLabel for="encounter_date" value="Encounter Date" />

                    <TextInput
                        id="encounter_date"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.encounter_date"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.encounter_date" />
                </div>
                <div class="mb-5 grid grid-cols-10 gap-4">
                    <InputLabel value="Item" class="col-span-4" />
                    <InputLabel value="Unit Price" class="col-span-2" />
                    <InputLabel value="Qty" />
                    <InputLabel value="Sub Total" class="col-span-2" />
                    <span></span>
                </div>
                <div v-for="(n, i) in form.items.length" :key="n" class="mb-5 grid grid-cols-10 gap-4">
                    <div class="col-span-4">
                        <TextInput
                            class="mt-1 block w-full"
                            v-model="form.items[i].name"
                            required
                        />
                    </div>
                    <div class="col-span-2">
                        <TextInput
                            class="mt-1 block w-full"
                            type="number"
                            step="0.01"
                            min="0"
                            v-model="form.items[i].unit_price"
                            :data-testid="`item-unit-price-${n}`"
                            required
                        />
                    </div>
                    <div class="">
                        <TextInput
                            class="mt-1 block w-full"
                            type="number"
                            min="1"
                            v-model="form.items[i].quantity"
                            :data-testid="`item-qty-${n}`"
                            required
                        />
                    </div>
                    <div class="col-span-2">
                        <TextInput
                            class="mt-1 block w-full"
                            :value="(form.items[i].unit_price * form.items[i].quantity).toLocaleString()"
                            :data-testid="`item-sub-total-${n}`"
                            disabled
                        />
                    </div>
                    <button type="button" @click="form.items.splice(i, 1)" :disabled="form.processing || form.items.length <= 1" class="mt-1 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3.5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :strokeWidth="1.5" stroke="currentColor" class="size-5">
                            <path strokeLinecap="round" strokeLinejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
                <div class="mb-5">
                    <PrimaryButton data-testid="add-item" type="button" class="text-white" @click="addAnotherItem" :disabled="form.processing">
                        Add Item
                    </PrimaryButton>
                </div>
                <div class="mb-5">
                    <InputLabel for="encounter_date" value="Order Total" />

                    <TextInput
                        class="mt-1 block w-full"
                        :value="total.toLocaleString()"
                        data-testid="order-total"
                        disabled
                    />
                </div>

                <PrimaryButton type="submit" class="text-white" :disabled="form.processing">
                    Submit
                </PrimaryButton>
            </form>
        </div>

        <Alerts />
    </GuestLayout>
</template>

<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Alerts from "@/Components/Alerts.vue";
import {computed} from "vue";

const form = useForm({
    provider_name: null,
    hmo_code: null,
    encounter_date: null,
    items: [
        {
            name: null,
            unit_price: null,
            quantity: null,
        }
    ],
});

const submit = () => {
    form.post(route('batch-order'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => form.reset(),
    });
};

const total = computed(() => {
    return form.items.reduce((result, currentValue) => result + (currentValue.quantity * currentValue.unit_price), 0);
});

const addAnotherItem = (e) => {
    form.items.push({
        name: null,
        quantity: null,
        unit_price: null,
    })
};
</script>
