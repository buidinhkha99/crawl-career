import { usePage, Link } from "@inertiajs/react";
import clsx from "clsx";
import { useState } from "react";

import { DropDown } from "../DropDown";

export default function Navigate({
    navigate,
    background,
    handleClose,
    onClickDropdown,
    onClickBackLang,
}) {
    const { url: path } = usePage();
    const urlActive = path.split("#");
    const url = urlActive[1]
        ? `${urlActive[0].split("?")[0]}#${urlActive[1]}`
        : urlActive[0].split("?")[0];

    const [show, setShow] = useState(true);
    const [isShowDropdown, setIsShowDropdown] = useState(true);
    const handleShow = (item) => {
        setShow(item);
    };
    const onClickShowDropdown = () => {
        setIsShowDropdown((prev) => !prev);
    };

    return (
        <div className="navigate relative">
            {navigate &&
                navigate.map((nav, index) => {
                    return (
                        <div key={nav.title + index}>
                            {show && !nav.nav_dropdown && (
                                <div>
                                    {nav.url?.startsWith("/") && (
                                        <>
                                            <Link
                                                className={clsx(
                                                    "navigate__link--xxl",
                                                    "navLink2Xl",
                                                    url === (nav.url || "/") &&
                                                        "active2Xl"
                                                )}
                                                href={`${nav.url}`}
                                            >
                                                {nav.title}
                                            </Link>
                                            <Link
                                                className={clsx(
                                                    "navigate__link--md",
                                                    "navLink",
                                                    url === (nav.url || "/") &&
                                                        "active"
                                                )}
                                                href={`${nav.url}`}
                                            >
                                                {nav.title}
                                            </Link>
                                            <Link
                                                className={clsx(
                                                    "navigate__link",
                                                    "navLinkMobile",
                                                    url === (nav.url || "/") &&
                                                        "activeMobile"
                                                )}
                                                href={`${nav.url}`}
                                            >
                                                {nav.title}
                                            </Link>
                                        </>
                                    )}
                                    {nav.url?.startsWith("https") && (
                                        <>
                                            <a
                                                className={clsx(
                                                    "navigate__link--xxl",
                                                    "navLink2Xl",
                                                    url === (nav.url || "/") &&
                                                        "active2Xl"
                                                )}
                                                href={nav.url}
                                                target="_blank"
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </a>
                                            <a
                                                className={clsx(
                                                    "navigate__link--md",
                                                    "navLink",
                                                    url === (nav.url || "/") &&
                                                        "active"
                                                )}
                                                href={nav.url}
                                                target="_blank"
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </a>
                                            <a
                                                className={clsx(
                                                    "navigate__link",
                                                    "navLinkMobile",
                                                    url === (nav.url || "/") &&
                                                        "activeMobile"
                                                )}
                                                href={nav.url}
                                                target="_blank"
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </a>
                                        </>
                                    )}

                                    {nav.url?.startsWith("#") && (
                                        <>
                                            <Link
                                                className={clsx(
                                                    "navigate__link--xxl",
                                                    "navLink2Xl",
                                                    url ===
                                                        (`${
                                                            urlActive[0].split(
                                                                "?"
                                                            )[0]
                                                        }${nav.url}` || "/") &&
                                                        "active2Xl"
                                                )}
                                                href={nav.url}
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </Link>
                                            <Link
                                                className={clsx(
                                                    "navigate__link--md",
                                                    "navLink",
                                                    url ===
                                                        (`${
                                                            urlActive[0].split(
                                                                "?"
                                                            )[0]
                                                        }${nav.url}` || "/") &&
                                                        "active"
                                                )}
                                                href={nav.url}
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </Link>
                                            <Link
                                                className={clsx(
                                                    "navigate__link",
                                                    "navLinkMobile",
                                                    url ===
                                                        (`${
                                                            urlActive[0].split(
                                                                "?"
                                                            )[0]
                                                        }${nav.url}` || "/") &&
                                                        "activeMobile"
                                                )}
                                                href={nav.url}
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </Link>
                                        </>
                                    )}
                                    {!nav.url && (
                                        <>
                                            <span
                                                className={clsx(
                                                    "navigate__link--xxl",
                                                    "navLink2Xl",
                                                    url ===
                                                        (`${
                                                            urlActive[0].split(
                                                                "?"
                                                            )[0]
                                                        }${nav.url}` || "/") &&
                                                        "active2Xl"
                                                )}
                                                href={nav.url}
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </span>
                                            <span
                                                className={clsx(
                                                    "navigate__link--md",
                                                    "navLink",
                                                    url ===
                                                        (`${
                                                            urlActive[0].split(
                                                                "?"
                                                            )[0]
                                                        }${nav.url}` || "/") &&
                                                        "active"
                                                )}
                                                href={nav.url}
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </span>
                                            <span
                                                className={clsx(
                                                    "navigate__link",
                                                    "navLinkMobile",
                                                    url ===
                                                        (`${
                                                            urlActive[0].split(
                                                                "?"
                                                            )[0]
                                                        }${nav.url}` || "/") &&
                                                        "activeMobile"
                                                )}
                                                href={nav.url}
                                                rel="noreferrer"
                                            >
                                                {nav.title}
                                            </span>
                                        </>
                                    )}
                                </div>
                            )}
                            {nav.nav_dropdown && (
                                <DropDown
                                    navDropdown={nav.nav_dropdown}
                                    title={nav.title}
                                    handleShow={handleShow}
                                    background={background}
                                    handleClose={handleClose}
                                    onClickDropdown={onClickDropdown}
                                    onClickBackLang={onClickBackLang}
                                    isShowDropdown={isShowDropdown}
                                    onClickShowDropdown={onClickShowDropdown}
                                />
                            )}
                        </div>
                    );
                })}
        </div>
    );
}
