import clsx from "clsx";
import moment from "moment";
import React from "react";

export default function CandidateInfo({
    avatar,
    full_name,
    identification_number,
    date_of_birth,
    coaching_team,
    work_unit,
    working_position,
    className,
    classNameInfo,
    classNamePaddingResult,
}) {
    return (
        <div
            className={clsx(
                "flex flex-col gap-[45px] px-auto w-[740px]",
                className
            )}
        >
            <h2 className="font-bold text-black text-4xl">
                Thông tin thí sinh
            </h2>
            <div className="flex flex-row items-start gap-[60px]">
                {avatar && (
                    <img
                        src={avatar}
                        alt="img user"
                        className="max-w-[200px] max-h-[200px]"
                    />
                )}
                <div
                    className={clsx(
                        classNamePaddingResult,
                        "flex flex-col gap-5 min-w-[400px]"
                    )}
                >
                    <div className={clsx(classNameInfo)}>
                        <span className="font-light min-w-[150px] text-base tracking-wide text-black">
                            Họ tên:
                        </span>
                        <span className="text-base font-bold tracking-wide text-black">
                            {full_name}
                        </span>
                    </div>
                    {identification_number && (
                        <div className={clsx(classNameInfo)}>
                            <span className="font-light min-w-[150px] text-base tracking-wide text-black">
                                Mã nhân viên:
                            </span>
                            <span className="text-base font-bold tracking-wide text-black">
                                {identification_number}
                            </span>
                        </div>
                    )}
                    <div className={clsx(classNameInfo)}>
                        <span className="font-light min-w-[150px] text-base tracking-wide text-black">
                            Ngày sinh:
                        </span>
                        <span className="text-base font-bold tracking-wide text-black">
                            {date_of_birth
                                ? moment(
                                      new Date(date_of_birth).toLocaleString(
                                          "en-US",
                                          {
                                              timeZone: "Asia/Singapore",
                                          }
                                      )
                                  ).format("DD/MM/YYYY")
                                : null}
                        </span>
                    </div>
                    {/*{coaching_team && (*/}
                    {/*    <div className={clsx(classNameInfo)}>*/}
                    {/*        <span className="font-light min-w-[150px] text-base tracking-wide text-black">*/}
                    {/*            Nhóm huấn luyện:*/}
                    {/*        </span>*/}
                    {/*        <span className="text-base font-bold tracking-wide text-black mr-2">*/}
                    {/*            {coaching_team}*/}
                    {/*        </span>*/}
                    {/*    </div>*/}
                    {/*)}*/}
                    {work_unit && (
                        <div className={clsx(classNameInfo)}>
                            <span className="font-light min-w-[150px] text-base tracking-wide text-black">
                                Chức vụ:
                            </span>
                            <span className="text-base font-bold tracking-wide text-black">
                                {work_unit}
                            </span>
                        </div>
                    )}
                    {working_position && (
                        <div className={clsx(classNameInfo)}>
                            <span className="font-light min-w-[150px] text-base tracking-wide text-black">
                                Bộ phận:
                            </span>
                            <span className="text-base font-bold tracking-wide text-black">
                                {working_position}
                            </span>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
