import React, { lazy, useState } from "react";
import { usePage } from "@inertiajs/react";
import { ToastContainer } from "react-toastify";
import clsx from "clsx";
import { ConfigProvider } from "antd";

import { Header, Footer, Breadcrumb } from "../shared";
import { message } from "../common/utils";
import { CloseIcon } from "../icon";

function CloseButton({ closeToast, ...props }) {
    return <CloseIcon onClick={closeToast} {...props} />;
}

export default function AppLayout({ children }) {
    const data = usePage().props;
    const {
        font_color,
        background,
        color_placeholder_input_form,
        background_input_form,
        color_border_input_form,
    } = data.setting;
    const sortOrder = Object.keys(data);
    const [orderHeader, setOrderHeader] = useState(
        sortOrder.findIndex((item) => item === "header")
    );
    const [orderMain, setOrderMain] = useState(
        sortOrder.findIndex((item) => item === "components")
    );
    const [orderFooter, setOrderFooter] = useState(
        sortOrder.findIndex((item) => item === "footer")
    );
    if (orderMain === -1) {
        if (orderFooter > orderHeader) {
            setOrderMain(orderHeader + 1);
            setOrderFooter(orderHeader + 2);
        }
        if (orderHeader > orderFooter) {
            setOrderMain(orderFooter + 2);
            setOrderHeader(orderFooter + 1);
        }
    }

    const clssaNameToast = (theme_toast) => {
        // example
        if (theme_toast === "light_blue") return "light_blue";
        return "white";
        // to do
    };
    const renderClassName = () => {
        if (data.header.type === "fixed") return "header-position";
    };

    return (
        <ConfigProvider
            theme={{
                token: {
                    fontFamily: [
                        "Roboto",
                        "Quicksand",
                        "Oswald",
                        "Advent Pro",
                        "Poppins",
                        "Inter",
                    ],
                },
                components: {
                    Typography: {
                        colorText: font_color,
                    },
                    Input: {
                        colorTextPlaceholder: `${color_placeholder_input_form} !important`,
                        colorBorder: `${color_border_input_form} !important`,
                        colorBgContainer: `${background_input_form} !important`,
                    },
                    InputNumber: {
                        colorTextPlaceholder: `${color_placeholder_input_form} !important`,
                        colorBorder: `${color_border_input_form} !important`,
                        colorBgContainer: `${background_input_form} !important`,
                    },
                },
            }}
        >
            <div className={clsx(clssaNameToast())}>
                <ToastContainer
                    position="bottom-left"
                    autoClose={3000}
                    newestOnTop={false}
                    closeOnClick
                    closeButton={CloseButton}
                    className="w-[330px] sm:w-[460px] md:w-[510px]"
                />
            </div>
            <div
                className="appLayout appLayoutVimico"
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                {/* Header */}
                {data.header && (
                    <div
                        style={{ order: orderHeader }}
                        className={clsx(renderClassName(), "min-h-[50px]")}
                    >
                        <Header header={data.header} />
                    </div>
                )}

                {/* Breadcumb */}
                {
                    <div style={{ order: orderHeader + 1 }}>
                        <Breadcrumb />
                    </div>
                }

                {/* Main */}
                {data.components && data.components.length > 0 ? (
                    <main
                        style={{ order: orderMain + 2 }}
                        className="mainLayoutVimico"
                    >
                        {children}
                    </main>
                ) : (
                    <main
                        style={{ order: orderMain + 2 }}
                        className="noComponent"
                    >
                        <h2 className="noComponent__h2">
                            There's no content here
                        </h2>
                        <p>To get started, let's create them in admin site</p>
                    </main>
                )}

                {/* Footer */}
                {data.footer && (
                    <div
                        style={{ order: orderFooter + 2 }}
                        className="min-h-[50px]"
                    >
                        <Footer footer={data.footer} />
                    </div>
                )}

                {/* add sub footer */}
                {/* <div
                    style={{
                        backgroundImage:
                            `url(${data.footer?.background?.data})` || null,
                        backgroundColor:
                            data.footer?.background?.data || "transparent",
                        order: orderFooter + orderHeader + orderMain + 10,
                    }}
                    className={clsx("subFooter backgroundImg")}
                >
                    <p className="subFooter__p">
                        Designed & Developed by{" "}
                        <a
                            href="https://brocos.io"
                            target="_blank"
                            className="subFooter__a"
                        >
                            BroCoS
                        </a>
                    </p>
                </div> */}
            </div>
        </ConfigProvider>
    );
}
