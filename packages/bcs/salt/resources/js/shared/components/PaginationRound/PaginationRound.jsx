import { useState } from "react";
import { Pagination } from "antd";
import clsx from "clsx";
import { router } from "@inertiajs/react";

import { moockApi } from "./PaginationRound.constants";

export default function PaginationRound({
    per_page,
    current_page,
    total,
    list_data,
}) {
    const [currentPage, setCurrenPage] = useState(current_page);
    const [data, setData] = useState(list_data);
    const handleChange = (pageNum, pageSize) => {
        router.visit("", {
            only: [],
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                setCurrenPage();
                setData();
            },
        });
    };
    const renderClassName = () => {
        if (moockApi.theme === "white") return "theme-white";
        return "theme-light-blue";
    };

    return (
        <div className={clsx(renderClassName())}>
            <Pagination
                showSizeChanger={false}
                pageSize={per_page}
                current={currentPage}
                total={total}
                onChange={handleChange}
                className="paginationRound"
            />
        </div>
    );
}
