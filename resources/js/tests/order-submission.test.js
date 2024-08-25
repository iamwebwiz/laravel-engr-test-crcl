import { mount } from "@vue/test-utils";
import { test, expect, vi } from "vitest";
import SubmitOrder from "@/Pages/SubmitOrder.vue";

vi.mock('vue-router', () => ({
    resolve: vi.fn(),
}));

vi.mock('@inertiajs/vue3',async (importOriginal) => ({
    __esModule: true,
    ...await importOriginal(),
    usePage: () => ({
        props: {
            flash: {
                alert: null
            }
        },
    })
}))

const totalSelector = '[data-testid=order-total]'
const addItemButtonSelector = '[data-testid=add-item]'

const unitPriceOneSelector = '[data-testid=item-unit-price-1]'
const quantityOneSelector = '[data-testid=item-qty-1]'
const subTotalOneSelector = '[data-testid=item-sub-total-1]'

const unitPriceTwoSelector = '[data-testid=item-unit-price-2]'
const quantityTwoSelector = '[data-testid=item-qty-2]'
const subTotalTwoSelector = '[data-testid=item-sub-total-2]'

const wrapper = mount(SubmitOrder, {})

test('the total field is empty or has a zero value by default', () => {
    expect(wrapper.find(totalSelector).element.value).to.be.oneOf(['0', ''])
    expect(wrapper.find(subTotalOneSelector).element.value).to.be.oneOf(['0', ''])
})

test('it has an accurate value for the total of the second line item', async () => {
    await wrapper.find(unitPriceOneSelector).setValue('12.45')
    await wrapper.find(quantityOneSelector).setValue('10')

    expect(wrapper.find(subTotalOneSelector).element.value).toBe(124.5.toLocaleString())
})

test('it has an accurate value for the total of the second line item', async () => {
    await wrapper.find(addItemButtonSelector).trigger('click');

    await wrapper.find(unitPriceTwoSelector).setValue('127.33')
    await wrapper.find(quantityTwoSelector).setValue('10')

    expect(wrapper.find(subTotalTwoSelector).element.value).toBe(1273.3.toLocaleString())
})

test('it has a correct value for the total of the order', () => {
    expect(wrapper.find(totalSelector).element.value).toBe(1397.8.toLocaleString())
})
