import clsx from "clsx";
import { Form, Input } from "antd";

const { TextArea } = Input;

export default function TextAreaSalt({
    placeholder,
    className,
    label,
    form_item_name,
    rules,
    itemKey,
    disabled,
    rows,
}) {
    return (
        <div key={itemKey}>
            <Form.Item name={form_item_name} rules={rules}>
                <TextArea
                    rows={5}
                    className={clsx(
                        "salt-textarea",
                        className,
                        disabled ? "salt-input__renderInput--disabled" : ""
                    )}
                    disabled={disabled}
                    placeholder={placeholder}
                    autoFocus={true}
                    label={label || null}
                />
            </Form.Item>
        </div>
    );
}
