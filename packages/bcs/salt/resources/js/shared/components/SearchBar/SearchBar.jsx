import { useState } from "react";
import { router } from "@inertiajs/react";
import { Input } from "antd";
import clsx from "clsx";

export default function SearchBar({
    className,
    inputRef,
    type,
    placeholder,
    icon,
}) {
    const [textSearch, setTextSearch] = useState("");
    const handleChangeText = (e) => {
        setTextSearch(e.target.value);
    };
    const handleSearch = (event) => {
        if (event.keyCode === 13) {
            router.get(
                `/search`,
                { "text-search": textSearch.trim() },
                {
                    only: [],
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (res) => {},
                    onError: (err) => {},
                }
            );
        }
    };
    const handleClickSearch = () => {
        router.get(
            `/search`,
            { "text-search": textSearch.trim() },
            {
                only: [],
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {},
                onError: (err) => {},
            }
        );
    };

    return (
        <div className="custom-searchBar">
            <Input
                className={clsx("custom-searchBar--borderInput", className)}
                ref={inputRef}
                prefix={
                    icon ? (
                        <div
                            className="header-moblie--cursorPoiter"
                            onClick={handleClickSearch}
                            dangerouslySetInnerHTML={{
                                __html: icon,
                            }}
                        />
                    ) : null
                }
                type={type}
                placeholder={placeholder}
                onChange={(e) => handleChangeText(e)}
                onKeyDown={(e) => handleSearch(e)}
            />
        </div>
    );
}
