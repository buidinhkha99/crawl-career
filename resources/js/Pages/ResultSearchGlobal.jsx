import { ResultSearch } from "../../../packages/bcs/salt/resources/js/shared";
import { usePage } from "@inertiajs/react";

export default function ResultSearchGlobal() {
    const {components}=usePage().props
    return <ResultSearch component={components} />;
}
