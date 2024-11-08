import { reactive } from "vue";

export default function useForm() {
    const values = reactive({
       'partnerNumber': 0,
       'partnerNames': [],
       'partnerContributions': [],
       'partnerShares': '',
       'partnerCountry': '',
       'partnerDate': '',
    })

    return {
        values,
    }
}