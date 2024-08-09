import { useState, useRef, useEffect } from "react";
import clsx from "clsx";
import { Link, usePage } from "@inertiajs/react";

import { BackIcon, DropIcon } from "../../../icon";

export default function DropDown({
    navDropdown,
    title,
    handleShow,
    background,
    handleClose,
    onClickDropdown,
    onClickBackLang,
    isShowDropdown,
    onClickShowDropdown,
}) {
    const { url: path } = usePage();
    const active = path.split("#");

    const pathActive = active[1]
        ? `${active[0].split("?")[0]}#${active[1]}`
        : active[0].split("?")[0];
    const [isShow, setIshow] = useState(false);
    const [isShowTwo, setIshowTwo] = useState(false);
    const [data, setData] = useState(null);
    const [isShowMobile, setIsShowMoblie] = useState(true);
    const [dataMobile, setDataMoblie] = useState(null);
    const [dataMobileTwo, setDataMobileTwo] = useState(null);
    const [isShowMobileTwo, setIsShowMobileTwo] = useState(true);
    const [isHover, setIsHover] = useState(false);
    const [urlActive, setUrlActive] = useState(
        navDropdown.map((itemUrl) => {
            if (itemUrl.nav_dropdown) {
                itemUrl.nav_dropdown.map((itemUrlChildren) => {
                    const urlChildren = itemUrlChildren.url;
                    return urlChildren;
                });
            }
            return itemUrl.url;
        })
    );
    const currentRef = useRef(null);

    useEffect(() => {
        document.addEventListener("click", handleClickOutSide, true);
    }, []);

    const handleClickOutSide = (e) => {
        if (currentRef.current && !currentRef.current.contains(e.target)) {
            setIshow(false);
            setIshowTwo(false);
            setIsHover((prev) => !prev);
        }
    };

    const handleClickDropdown = () => {
        setIsHover((prev) => !prev);
        setIshow((prev) => !prev);
        if (isShowTwo) {
            setIshow(false);
            setIshowTwo(false);
        }
    };

    const handleClickDropdownTwo = (item) => {
        setIshow(false);
        setIshowTwo(true);
        setData(item);
    };
    const handleClickBack = () => {
        setIshow(true);
        setIshowTwo(false);
    };

    const handleClickDropdownMobile = (itemMobile) => {
        setIsShowMoblie(false);
        setDataMoblie(itemMobile);
        handleShow(false);
        onClickDropdown();
        onClickShowDropdown();
    };

    const handleClickBackMobile = () => {
        setIsShowMoblie(true);
        handleShow(true);
        onClickBackLang();
        onClickShowDropdown();
    };

    const handleClickMobile = (itemMobileTwo) => {
        setIsShowMobileTwo(false);
        setDataMobileTwo(itemMobileTwo);
    };

    const handleClickBackMobileTwo = () => {
        setIsShowMobileTwo(true);
    };

    const handleClickPath = (path) => {
        setIshow((prev) => !prev);
    };

    const handleClickPathTwo = (path) => {
        setIshowTwo((prev) => !prev);
    };

    const handleClickPathMobile = (path) => {
        handleClose();
    };

    return (
        <>
            <div className="dropDown--md">
                <div
                    className={clsx(
                        "navLink2Xl",
                        (active[1]
                            ? urlActive.includes(`#${active[1]}`)
                            : urlActive.includes(pathActive)) && "active2Xl",
                        isHover && "active2Xl",
                        "dropDown--navLink2xl"
                    )}
                    onClick={handleClickDropdown}
                >
                    <span className="dropDown--title">{title}</span>
                    <DropIcon />
                </div>
                <div
                    className={clsx(
                        "navLink",
                        (active[1]
                            ? urlActive.includes(`#${active[1]}`)
                            : urlActive.includes(pathActive)) && "active",
                        isHover && "active",
                        "dropDown--navLink"
                    )}
                    onClick={handleClickDropdown}
                >
                    <span className="dropDown--title">{title}</span>
                    <DropIcon />
                </div>

                {isShow && (
                    <div
                        className="dropDown--two backgroundImg"
                        style={{
                            backgroundColor: background.data || "transparent",
                            backgroundImage: `url(${background?.data})` || null,
                        }}
                        ref={currentRef}
                    >
                        {navDropdown.map((itemNav, index) => {
                            return (
                                <div
                                    className="dropDown--two--flex"
                                    key={itemNav.title + index + itemNav.url}
                                >
                                    {!itemNav.nav_dropdown && (
                                        <>
                                            {itemNav.url?.startsWith("/") && (
                                                <Link
                                                    href={`${itemNav.url}`}
                                                    onClick={() =>
                                                        handleClickPath(
                                                            itemNav.url
                                                        )
                                                    }
                                                    className="dropdown--hover"
                                                >
                                                    {itemNav.title}
                                                </Link>
                                            )}
                                            {itemNav.url?.startsWith(
                                                "https"
                                            ) && (
                                                <a
                                                    href={itemNav.url}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    onClick={() =>
                                                        handleClickPath(
                                                            itemNav.url
                                                        )
                                                    }
                                                    className="dropdown--hover"
                                                >
                                                    {itemNav.title}
                                                </a>
                                            )}
                                            {itemNav.url?.startsWith("#") && (
                                                <Link
                                                    href={itemNav.url}
                                                    rel="noreferrer"
                                                    onClick={() =>
                                                        handleClickPath(
                                                            itemNav.url
                                                        )
                                                    }
                                                    className="dropdown--hover"
                                                >
                                                    {itemNav.title}
                                                </Link>
                                            )}
                                        </>
                                    )}
                                    {itemNav.nav_dropdown && (
                                        <div
                                            className="dropDown--two--icon dropdown--hover"
                                            onClick={() =>
                                                handleClickDropdownTwo(
                                                    itemNav.nav_dropdown
                                                )
                                            }
                                            key={
                                                itemNav.title +
                                                index +
                                                itemNav.url
                                            }
                                        >
                                            <span className="dropDown--title">
                                                {itemNav.title}
                                            </span>
                                            <DropIcon />
                                        </div>
                                    )}
                                </div>
                            );
                        })}
                    </div>
                )}
                {isShowTwo && (
                    <div
                        className="dropDown--three backgroundImg"
                        style={{
                            backgroundColor: background.data || "transparent",
                            backgroundImage: `url(${background?.data})` || null,
                        }}
                        ref={currentRef}
                    >
                        <div
                            className="dropDown--three--flex"
                            onClick={handleClickBack}
                        >
                            <BackIcon className="dropDown--title" />
                            <span className="butonBack">Back</span>
                        </div>
                        {data.map((itemNavTwo, index) => {
                            return (
                                <div
                                    key={
                                        itemNavTwo.title +
                                        index +
                                        itemNavTwo.url
                                    }
                                    className="dropdown--hover"
                                >
                                    {itemNavTwo.url?.startsWith("/") && (
                                        <Link
                                            href={`${itemNavTwo.url}`}
                                            onClick={() =>
                                                handleClickPathTwo(
                                                    itemNavTwo.url
                                                )
                                            }
                                        >
                                            {itemNavTwo.title}
                                        </Link>
                                    )}
                                    {itemNavTwo.url?.startsWith("https") && (
                                        <a
                                            href={itemNavTwo.url}
                                            target="_blank"
                                            rel="noreferrer"
                                            onClick={() =>
                                                handleClickPathTwo(
                                                    itemNavTwo.url
                                                )
                                            }
                                        >
                                            {itemNavTwo.title}
                                        </a>
                                    )}
                                    {itemNavTwo.url?.startsWith("#") && (
                                        <a
                                            href={itemNavTwo.url}
                                            rel="noreferrer"
                                            onClick={() =>
                                                handleClickPathTwo(
                                                    itemNavTwo.url
                                                )
                                            }
                                        >
                                            {itemNavTwo.title}
                                        </a>
                                    )}
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
            <div className="dropDown--mobile">
                {isShowMobile && isShowDropdown && (
                    <div
                        className={clsx(
                            (active[1]
                                ? urlActive.includes(`#${active[1]}`)
                                : urlActive.includes(pathActive)) &&
                                "activeMobile",
                            "navLinkMobile",
                            "dropDown--mobile--navLink"
                        )}
                        onClick={() => handleClickDropdownMobile(navDropdown)}
                    >
                        <span className="dropDown--title">{title}</span>
                        <DropIcon />
                    </div>
                )}
                {!isShowMobile && isShowMobileTwo && (
                    <div className="dropDown--mobile--flex">
                        <div
                            className="dropDown--mobile--icon"
                            onClick={handleClickBackMobile}
                        >
                            <BackIcon className="dropDown--title" />
                            <span className="butonBack">Back</span>
                        </div>
                        {dataMobile.map((navMobile, index) => {
                            return (
                                <>
                                    {isShowMobileTwo && (
                                        <div
                                            className="dropDown--mobile--two"
                                            key={
                                                navMobile +
                                                navMobile.title +
                                                index
                                            }
                                        >
                                            {navMobile.nav_dropdown ? (
                                                <div
                                                    className="dropDown--mobile--icon dropdown--hover"
                                                    onClick={() =>
                                                        handleClickMobile(
                                                            navMobile.nav_dropdown
                                                        )
                                                    }
                                                >
                                                    <span className="dropDown--title">
                                                        {navMobile.title}
                                                    </span>
                                                    <DropIcon />
                                                </div>
                                            ) : (
                                                <>
                                                    {navMobile.url?.startsWith(
                                                        "/"
                                                    ) && (
                                                        <Link
                                                            key={
                                                                navMobile.title +
                                                                index +
                                                                navMobile.url
                                                            }
                                                            href={`${navMobile.url}`}
                                                            className="dropdown--hover"
                                                            onClick={() =>
                                                                handleClickPathMobile(
                                                                    navMobile.url
                                                                )
                                                            }
                                                        >
                                                            {navMobile.title}
                                                        </Link>
                                                    )}
                                                    {navMobile.url?.startsWith(
                                                        "https"
                                                    ) && (
                                                        <a
                                                            key={
                                                                navMobile.title +
                                                                index +
                                                                navMobile.url
                                                            }
                                                            href={navMobile.url}
                                                            rel="noreferrer"
                                                            target="_blank"
                                                            className="dropdown--hover"
                                                            onClick={() =>
                                                                handleClickPathMobile(
                                                                    navMobile.url
                                                                )
                                                            }
                                                        >
                                                            {navMobile.title}
                                                        </a>
                                                    )}
                                                    {navMobile.url?.startsWith(
                                                        "#"
                                                    ) && (
                                                        <Link
                                                            href={navMobile.url}
                                                            className="dropdown--hover"
                                                            onClick={() =>
                                                                handleClickPathMobile(
                                                                    navMobile.url
                                                                )
                                                            }
                                                        >
                                                            {navMobile.title}
                                                        </Link>
                                                    )}
                                                </>
                                            )}
                                        </div>
                                    )}
                                </>
                            );
                        })}
                    </div>
                )}
                {!isShowMobileTwo && (
                    <div className="mobileTwo">
                        <div
                            className="mobileTwoBack "
                            onClick={handleClickBackMobileTwo}
                        >
                            <BackIcon className="dropDown--title" />
                            <span className="butonBack">Back</span>
                        </div>
                        {dataMobileTwo.map((itemMobileTwo, index) => {
                            return (
                                <div
                                    key={
                                        itemMobileTwo.title +
                                        index +
                                        itemMobileTwo.url
                                    }
                                >
                                    {itemMobileTwo.url?.startsWith("/") && (
                                        <Link
                                            href={`${itemMobileTwo.url}`}
                                            className="dropdown--hover"
                                            onClick={() =>
                                                handleClickPathMobile(
                                                    itemMobileTwo.url
                                                )
                                            }
                                        >
                                            {itemMobileTwo.title}
                                        </Link>
                                    )}
                                    {itemMobileTwo.url?.startsWith("https") && (
                                        <a
                                            href={itemMobileTwo.url}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="dropdown--hover"
                                            onClick={() =>
                                                handleClickPathMobile(
                                                    itemMobileTwo.url
                                                )
                                            }
                                        >
                                            {itemMobileTwo.title}
                                        </a>
                                    )}
                                    {itemMobileTwo.url?.startsWith("#") && (
                                        <Link
                                            href={itemMobileTwo.url}
                                            className="dropdown--hover"
                                            onClick={() =>
                                                handleClickPathMobile(
                                                    itemMobileTwo.url
                                                )
                                            }
                                        >
                                            {itemMobileTwo.title}
                                        </Link>
                                    )}
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
        </>
    );
}
