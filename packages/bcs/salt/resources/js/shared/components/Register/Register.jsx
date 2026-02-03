import { Suspense } from "react";
import { Row, Col, Form } from "antd";

import { Loading } from "../Loading";
import { moockApi } from "./Register.constants";
import { Container } from "../../Container";
import { renderInput } from "../../../helpers/renderInput";
import { formRegister } from "../../../common/constants";
import { Button } from "../Button";

export default function Register({ background, indexItem }) {
    const [form] = Form.useForm();
    const handleFinish = (values) => {
        // to do
    };

    return (
        <Suspense fallback={<Loading />}>
            <div
                className="register backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <Row
                        gutter={{
                            xxl: 80,
                            xl: 60,
                            lg: 40,
                            md: 30,
                            sm: 20,
                            xs: 20,
                        }}
                        className="register__row"
                    >
                        <Col xxl={12} lg={12} xs={24}>
                            {moockApi.img && (
                                <div className="register__img">
                                    <img
                                        src={moockApi.img}
                                        alt="img register"
                                    />
                                </div>
                            )}
                        </Col>
                        <Col
                            xxl={12}
                            lg={12}
                            xs={24}
                            className="register--info"
                        >
                            <h2 className="register__h2">Register</h2>
                            <Form
                                form={form}
                                onFinish={handleFinish}
                                className="register__form"
                            >
                                {formRegister?.map((item, index) => (
                                    <div key={item.placeholder + index}>
                                        {renderInput(item.type, {
                                            layout: "icon",
                                            form_item_name: item.name,
                                            disabled: false,
                                            rounded_full: item.rounded_full,
                                            rules: item.rules,
                                            inputType: item.type,
                                            placeholder: item.placeholder,
                                            prefix: null,
                                        })}
                                    </div>
                                ))}
                                <Form.Item>
                                    {(moockApi.config.icon?.data ||
                                        moockApi.config.text) && (
                                        <Button
                                            config={moockApi.config}
                                            className="register__button"
                                        />
                                    )}
                                </Form.Item>
                            </Form>
                        </Col>
                    </Row>
                </Container>
            </div>
        </Suspense>
    );
}
