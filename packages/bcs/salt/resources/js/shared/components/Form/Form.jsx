import clsx from "clsx";
import { Form } from "antd";
import { useRef } from "react";
import { router } from "@inertiajs/react";
import { usePage } from "@inertiajs/react";
import ReCAPTCHA from "react-google-recaptcha";

import "./Form.css";
import { Button } from "../Button";
import { message } from "../../../common/utils";
import { renderInput } from "../../../helpers/renderInput";

export default function FormSalt({ background, indexItem }) {
    const config = usePage().props.footer.components[indexItem];
    const { color_text_title_form } = usePage().props?.setting;
    const { size, button, title, inputs, form_id } = config;
    const [form] = Form.useForm();
    const recaptchaRef = useRef(null);
    const checkSize = () => {
        if (size === "large") return "lg";
        return "md";
    };

    const handleFinish = (values) => {
        values["g-recaptcha-response"] = recaptchaRef.current.getValue();
        values["form_id"] = form_id;
        router.post("/form", values, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (res) => {
                message.success(res.props.flash.message, 3);
                form.resetFields();
            },
            onError: (err) => {
                message.error(err.message, 3);
            },
        });
    };

    const sizeCaptcha = () => {
        if (window.innerWidth >= 576) return "normal";
        return "compact";
    };

    return (
        <Form
            form={form}
            onFinish={handleFinish}
            className={clsx(checkSize(), "salt-form")}
            fields={inputs?.map((item) => ({
                name: [item.name],
                value: item.default,
            }))}
        >
            <h2
                className="title form__h2"
                style={{ color: color_text_title_form }}
            >
                {title}
            </h2>

            {/* Render Inputs */}
            {inputs?.map((item, index) => (
                <div key={item.placeholder + index}>
                    {renderInput(item.type, {
                        layout: item.layout,
                        rows: item.rows,
                        form_item_name: item.name,
                        disabled: item.disabled,
                        rounded_full: item.rounded_full,
                        rules: item.rules,
                        inputType: item.type,
                        placeholder: item.placeholder,
                        prefix: item.icon,
                        suffix: item.suffix,
                    })}
                </div>
            ))}

            <ReCAPTCHA
                sitekey={import.meta.env.VITE_REACT_APP_RECAPTCHA_SITE_KEY}
                size={sizeCaptcha()}
                theme="light"
                ref={recaptchaRef}
                className="recaptcha g-recaptcha"
            />

            <Form.Item className="form--item__button">
                {(button.icon?.data || button.text) && (
                    <Button
                        config={button}
                        className={`custom-button-form`}
                        handleClickButton={() => {}}
                    />
                )}
            </Form.Item>
        </Form>
    );
}
