import { useState, useMemo, useRef, useEffect } from "react";
import { Drawer, Modal } from "antd";
import { router, usePage } from "@inertiajs/react";
import qs from "qs";
import Cookies from "js-cookie";

import { Button, Logo, Navigate, SearchBar } from "../components";
import {
    LanguageIcon,
    MenuIcon,
    DropIcon,
    BackIcon,
    SearchIcon,
    CloseSearchIcon,
} from "../../icon";
import { Container } from "../Container";
import { paramSerializer } from "../../common/utils";

export default function Header({ header }) {
    const { title, lang } = usePage().props;
    const { url } = usePage();
    document.title = `${title || "BCS"}`;
    const [isOpenMenu, setIsOpenMenu] = useState(false);
    const [isShowLanguage, setIsShowLanguage] = useState(false);
    const [isLangMobile, setLanguageMobile] = useState(false);
    const [isLang, setIsLang] = useState(true);
    const [isShowSearch, setIsShowSearch] = useState(true);

    const [language, setLanguage] = useState(
        header.language.language_list.find((item) => item.key === lang)
    );
    const currentRef = useRef(null);
    const inputRef = useRef(null);

    useEffect(() => {
        document.addEventListener("click", handleClickOutSide, true);
    }, []);

    const handleClickOutSide = (e) => {
        if (currentRef.current && !currentRef.current.contains(e.target)) {
            setIsShowLanguage(false);
        }
    };

    Cookies.set("lang=", lang);

    const paramObject = useMemo(() => qs.parse(url.split("?")[1]), [url]);

    const handleClickOpenMenu = () => {
        setIsOpenMenu((prev) => !prev);
    };
    const handleClose = () => {
        setIsOpenMenu(false);
    };

    const onClickDropdown = () => {
        setIsLang(false);
    };

    const onClickBackLang = () => {
        setIsLang(true);
    };
    const handleClickLanguage = () => {
        setIsShowLanguage((prev) => !prev);
    };
    const handleChangeLanguage = (item) => {
        setIsShowLanguage(false);
        setLanguage(item);
        Cookies.set("lang=", item.key);
        router.visit(
            `?${paramSerializer({ ...paramObject, lang: `${item.key}` })}`
        );
    };

    const handleChangeLanguageMobile = (item) => {
        setLanguageMobile(false);
        setLanguage(item);
        Cookies.set("lang=", item.key);
        router.visit(
            `?${paramSerializer({ ...paramObject, lang: `${item.key}` })}`
        );
    };

    const handleClickIconSearch = () => {
        setIsShowSearch((prev) => !prev);
        setTimeout(() => inputRef.current.focus(), 0);
    };

    const handleCloseSearch = () => {
        setIsShowSearch((prev) => !prev);
    };

    const SectionLanguage = () => {
        if (header.language?.language_list.length <= 1) {
            return null;
        }

        return (
            <div className="language">
                <div className="language--flex" onClick={handleClickLanguage}>
                    <LanguageIcon />
                    <span className="language--dropdown--text">
                        {language.value}
                    </span>
                    <DropIcon />
                </div>
                {isShowLanguage && (
                    <div
                        className="language--dropdown backgroundImg"
                        style={{
                            backgroundColor:
                                header.background?.data || "transparent",
                            backgroundImage:
                                `url(${header.background?.data})` || null,
                        }}
                        ref={currentRef}
                    >
                        <div className="language--dropdown--flex">
                            {header.language?.language_list.map(
                                (item, index) => {
                                    return (
                                        <span
                                            className="language--dropdown--item"
                                            onClick={() =>
                                                handleChangeLanguage(item)
                                            }
                                            key={index + item.key}
                                        >
                                            {item.value}
                                        </span>
                                    );
                                }
                            )}
                        </div>
                    </div>
                )}
            </div>
        );
    };

    const SectionLanguageMobile = () => {
        if (header.language?.language_list.length <= 1) {
            return null;
        }

        return (
            <div className="langMobile">
                {!isLangMobile && isLang && (
                    <div
                        className="langMobile--dropdown"
                        onClick={() => setLanguageMobile((prev) => !prev)}
                    >
                        <LanguageIcon />
                        <span className="langMobile--dropdown--language">
                            {language.value}
                        </span>
                        <DropIcon />
                    </div>
                )}
                {isLangMobile && (
                    <div className="mobilelanguage">
                        <div
                            className="langBack"
                            onClick={() => setLanguageMobile((prev) => !prev)}
                        >
                            <BackIcon />
                            <span className="langBack--language">{lang}</span>
                        </div>
                        <div className="langBack__div">
                            {header.language?.language_list.map(
                                (item, index) => {
                                    return (
                                        <span
                                            className="langBack__div--item"
                                            onClick={() =>
                                                handleChangeLanguageMobile(item)
                                            }
                                            key={index + item.key}
                                        >
                                            {item.value}
                                        </span>
                                    );
                                }
                            )}
                        </div>
                    </div>
                )}
            </div>
        );
    };

    return (
        <header
            className="header backgroundImg"
            id={header?.id}
            style={{
                backgroundImage: `url(${header.background?.data})` || null,
                backgroundColor: header.background?.data || "transparent",
                zIndex: 2000,
            }}
        >
            <Container className="header__container">
                <div className="header--flex">
                    <div className="header--logo">
                        {header.logo && <Logo logo={header.logo} alt={title} />}
                    </div>
                    <div className="headerRight">
                        <div className="header--navigative">
                            {header.nav && (
                                <Navigate
                                    navigate={header.nav}
                                    background={header.background}
                                />
                            )}
                        </div>
                        {/* search must be an object

                            BE is returning an array
                        */}
                        {header.search.length > 0 && header.search.type && (
                            <SearchBar
                                className="searchbar--lg"
                                type={header.search.type}
                                placeholder={header.search.placeholder}
                                icon={header.search.icon}
                            />
                        )}
                        <SectionLanguage />
                        {header.config_button_logout && (
                            <Button
                                config={header.config_button_logout}
                                handleClickButton={() => {}}
                            />
                        )}
                    </div>
                    <div className="header-moblie">
                        {isShowSearch &&
                            header.search.length > 0 &&
                            header.search.type &&
                            (header.search.icon ? (
                                <div
                                    className="header-moblie--icon"
                                    onClick={handleClickIconSearch}
                                    dangerouslySetInnerHTML={{
                                        __html: header.search.icon,
                                    }}
                                />
                            ) : (
                                <SearchIcon
                                    className="header-moblie--cursorPoiter"
                                    onClick={handleClickIconSearch}
                                />
                            ))}
                        {header.nav && (
                            <MenuIcon
                                className="header-moblie--cursorPoiter"
                                onClick={handleClickOpenMenu}
                            />
                        )}
                    </div>
                </div>
                {header.search.length > 0 && header.search.type && (
                    <div className="custom-modal">
                        <Modal
                            closable={false}
                            fontsize="32px"
                            top
                            className="custom-modal-search"
                            open={!isShowSearch}
                            footer={null}
                            width={1000}
                            bodyStyle={{
                                backgroundImage:
                                    `url(${header.background?.data})` || null,
                                backgroundColor:
                                    header.background?.data || "transparent",
                                zIndex: 2000,
                                padding: "30px",
                                backgroundRepeat: "no-repeat",
                                backgroundSize: "cover",
                                backgroundPosition: "center",
                            }}
                        >
                            <div className="custom-modal__div">
                                <SearchBar
                                    inputRef={inputRef}
                                    type={header.search.type}
                                    placeholder={header.search.placeholder}
                                    icon={header.search.icon}
                                />
                                <CloseSearchIcon
                                    onClick={handleCloseSearch}
                                    className="custom-modal--icon"
                                />
                            </div>
                        </Modal>
                    </div>
                )}
            </Container>

            <Drawer
                closable={false}
                placement={"right"}
                onClose={handleClose}
                open={isOpenMenu}
                className="drawerHederMobile backgroundImg"
                style={{
                    backgroundImage: `url(${header.background?.data})` || null,
                    backgroundColor: header.background?.data || "transparent",
                }}
                width={210}
            >
                <div className="headerMobile">
                    {!isLangMobile && header.nav && (
                        <Navigate
                            navigate={header.nav}
                            background={header.background}
                            handleClose={handleClose}
                            onClickDropdown={onClickDropdown}
                            onClickBackLang={onClickBackLang}
                        />
                    )}

                    <SectionLanguageMobile />
                </div>
            </Drawer>
        </header>
    );
}
