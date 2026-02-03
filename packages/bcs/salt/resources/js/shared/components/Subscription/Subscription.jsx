import clsx from "clsx";
import { Form } from "antd";
import React, { Suspense } from "react";
import { usePage } from "@inertiajs/react";

import Loading from "../Loading/Loading";
import { router } from "@inertiajs/react";
import { Container } from "../../Container";
import { SubHeading } from "../SubHeading";
import { message } from "../../../common/utils";
import { mockApi } from "./Subscription.constants";
import { renderInput } from "../../../helpers/renderInput";

export default function Subscription({ background, indexItem }) {
    const [form] = Form.useForm();
    const { input } = mockApi;
    const config = usePage().props.components[indexItem];

    const checkSize = () => {
        if (mockApi.size === "large") return "lg";
        return "md";
    };

    const handleFinish = (values) => {
        router.post(
            "/subscribe",
            { ...values },
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                    message.success(res.props.flash.message, 3);
                    form.resetFields();
                },
                onError: (err) => {
                    Object.keys(err).forEach(function (key, index) {
                        message.error(err[key], 3);
                    });
                },
            }
        );
    };

    return (
        <div
            id={config.id}
            className="backgroundImg"
            style={{
                backgroundImage: `url(${background?.data})` || null,
                backgroundColor: background?.data || "transparent",
            }}
        >
            <Suspense fallback={<Loading />}>
                <div className="subscription">
                    <Container>
                        <div className="subscription__container">
                            <SubHeading>{config.title}</SubHeading>
                            <p className="subscription--content">
                                {config.content}
                            </p>
                            <Form
                                form={form}
                                onFinish={handleFinish}
                                className={clsx(
                                    checkSize(),
                                    "salt-form",
                                    "subscription__form"
                                )}
                            >
                                {renderInput("text", {
                                    layout: input.layout,
                                    form_item_name: input.name,
                                    disabled: input.disabled,
                                    rounded_full: input.rounded_full,
                                    rules: input.rules,
                                    inputType: "text",
                                    placeholder: input.placeholder,
                                    className: "subscription__form--email",
                                    prefix: input.icon,
                                    suffix: input.config_button,
                                })}
                            </Form>
                        </div>
                    </Container>
                </div>
            </Suspense>
        </div>
    );
}
